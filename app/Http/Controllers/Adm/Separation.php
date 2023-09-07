<?php

namespace App\Http\Controllers\Adm;

use App\Models\History\History;
use App\Models\OrderStocks;
use App\Models\Stocks;
use App\State\Order\StateOrderExitConference;
use App\State\OrderItems\StateOrderExitItemConference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;

use App\Services\OrderService;
use App\State\Order\StateOrderExitSeparation;
use App\State\OrderItems\StateOrderExitItemSeparate;

use App\Models\Partners;
use App\Models\Transports;
use App\Models\OrderExits;
use App\Models\OrderItemExits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Separation extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:separacao-pedidos|ver-separacao-pedidos|ver-detalhes-separacao-pedidos|imprimir-separacao-pedidos|separar-separacao-pedidos
            |cadastrar-separacao-pedidos|inativar-separacao-pedidos|editar-separacao-pedidos|deletar-separacao-pedidos',
            ['only' => [
                'index', 'save', 'getListOrders', 'print',
                'inBatch', 'manager', 'getListOrderItems', 'separateNextItem', 'separateItem', 'sendToConference'
            ]]
        );
    }

    /**
     * PAGINA INICIAL
     */
    public function index()
    {
        return view('adm.separation');
    }

    /**
     * RETORNA LISTA DE PEDIDOS EM JSON
     * @return void
     * @throws \Exception
     */
    public function getListOrders() {

        $user = Auth::user();

        if($orderExits = OrderExits::
            where('type',OrderExits::type('Saída'))
            ->where('status',OrderExits::status(StateOrderExitSeparation::class))
            ->orderBy('id','desc')
            ->get()){
            foreach ($orderExits as $orderExit){

                $imprimir = $user->hasPermissionTo('imprimir-separacao-pedidos') ?
                "<a href=\"" . route('adm.separation.print', ['orderExit' => $orderExit->id]) . "\" class=\"badge badge-primary\" target='_blank'><i class=\"fas fa-print\"></i> IMPRIMIR</a>"
                : '';

               $detalhes = $user->hasPermissionTo('ver-detalhes-separacao-pedidos') ?
                "<a href=\"" . route('adm.separation.manager', ['orderExit' => $orderExit->id]) . "\" class=\"badge badge-success ml-2\"><i class=\"fas fa-info\"></i> DETALHES</a>"
                   : '';

                $data['data'][] = [
                    date_fmt($orderExit->created_at, 'd/m/Y H:m'),
                    $orderExit->id,
                    $orderExit->third_system_id,
                    $orderExit->office->name,
                    $orderExit->partner->name,
                    $orderExit->status,
                    date_fmt($orderExit->forecast, 'd/m/Y'),
                    $imprimir . $detalhes
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function print(OrderExits $orderExit)
    {
        $items = (new OrderService())->sortOrderItemsByAddressingConvertedSimpleArray($orderExit);
        return view('adm.printables.separation', compact('orderExit', 'items'));
    }

    public function inBatch(Request $request)
    {

        if (!$orderExit = OrderExits::find((int) $request->order_id)) {
            echo (new Response())->error('Ficha não encontrada')->json();
            return;
        }

        try {

            DB::beginTransaction();

            foreach ($orderExit->items as $orderItem){

                /* Procura Posições no Estoque */
                $stocks = $this->getStocks($orderExit, $orderItem);
                /* Checa Existencia */
                if($stocks->isEmpty()){
                    throw new \Exception("Ops... Não temos Estoque do ISBN: {$orderItem->isbn}");
                }
                /* Checa Quantidade */
                if(($orderItem->separated + $stocks->sum('quantity')) < $orderItem->quantity){
                    throw new \Exception("Ops... Não temos Quantidade Necessária do ISBN: {$orderItem->isbn}");
                }

                /* Inclui Itens a Separar */
                foreach ($stocks as $stock){
                    $remainingAmount = $orderItem->quantity - $orderItem->separated;

                    /* Se Já Completou a Quantidade, Para de Separar*/
                    if($remainingAmount < 1){
                        break;
                    }

                    /* Determina a Quantidade Que Vai Separar Neste Endereçamento (Estoque) */
                    if($stock->quantity < $remainingAmount){
                        $separateInThisAddress = $stock->quantity;
                    } else {
                        $separateInThisAddress = $remainingAmount;
                    }

                    /* Da Baixa No Estoque */
                    $stock->quantity = $stock->quantity - $separateInThisAddress;
                    $stock->save();

                    /* Atualiza o Item do Pedido */
                    $orderItem->separated += $separateInThisAddress;
                    $orderItem->save();

                    /* Grava da Onde Foi Retirado o Item */
                    if($orderStock = OrderStocks::where('order_item_id',$orderItem->id)->where('stock_id',$request->stock_id)->first()){
                        $orderStock->separate_quantity += $separateInThisAddress;
                        $orderStock->save();
                    } else {
                        OrderStocks::create([
                            'partner_id'        => $orderExit->partner_id,
                            'order_id'          => $orderExit->id,
                            'order_item_id'     => $orderItem->id,
                            'stock_id'          => $stock->id,
                            'separate_quantity' => $separateInThisAddress
                        ]);
                    }
                }

                $orderItem->status = OrderItemExits::status(StateOrderExitItemConference::class);
                $orderItem->save();

            }

            $orderExit->status = OrderExits::status(StateOrderExitConference::class);
            if (!$orderExit->save()) {
                throw new \Exception("Ops... Falha ao salvar pedido");
            }

            /* Histórico */
            (new History($orderExit))->description("Liberou o pedido para conferência (em lote com baixa)")->save();

            DB::commit();
            echo (new Response())->success("Separação Realizada com Sucesso")
                ->action('reloadDataTable', 'table')->json();
        } catch (\Exception $exception) {
            DB::rollBack();
            echo (new Response())->error($exception->getMessage())->json();
            return;
        }
    }

    /**
     * GERENCIAR SEPARAÇÃO
     * @param OrderExits $orderExit
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function manager(OrderExits $orderExit)
    {
        $dropModality = Transports::MODALITY;
        $dropPackaging = Transports::PACKAGING;
        $dropTransport = Partners::get(['id', 'name'])->toArray();
        return view('adm.separationManager', compact('orderExit', 'dropModality', 'dropPackaging', 'dropTransport'));
    }

    /**
     * RETORNA A LISTA ORDENADA DOS ITENS DO PEDIDO
     * @param OrderExits $orderExit
     * @return void
     */
    public function getListOrderItems(OrderExits $orderExit)
    {

        if ($orderItems = (new OrderService())->sortOrderItemsByAddressingConvertedSimpleArray($orderExit)) {
            foreach ($orderItems as $addressing) {
                $backgroundBadge = ($addressing->quantity == $addressing->separated ? 'success' : 'danger');
                $data['data'][] = [
                    $addressing->addressing,
                    $addressing->isbn,
                    $addressing->title,
                    $addressing->publisher,
                    $addressing->available,
                    $addressing->quantity,
                    $addressing->separated,
                    "<span class=\"badge badge-{$backgroundBadge} px-2\">{$addressing->status}</span>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * RETORNA QUAL O PROXIMO ITEM A SER SEPARADO
     * @param OrderExits $orderExit
     * @return void
     */
    public function separateNextItem(OrderExits $orderExit)
    {

        if ($orderExit->status == OrderExits::status(StateOrderExitConference::class)) {
            echo (new Response())->error('Pedido ' . OrderExits::status(StateOrderExitConference::class))->json();
            return;
        }

        if ($orderItems = (new OrderService())->sortOrderItemsByAddressingConvertedSimpleArray($orderExit)) {
            foreach ($orderItems as $orderItem) {
                if ($orderItem->available > 0 && $orderItem->separated < $orderItem->quantity) {
                    $data = [
                        'stock_id'          => $orderItem->stock_id,
                        'addressing_hdn'    => $orderItem->addressing,
                        'isbn_hdn'          => $orderItem->isbn
                    ];
                    echo (new Response())->action('loadForm', 'form-separate')->data($data)->json();
                    return;
                }
            }
            echo (new Response())->info('Não há itens à separar :)')->json();
        }
    }

    /**
     * FAZ A SEPARAÇÃO DE UM ITEM
     * @param OrderExits $orderExit
     * @param Request $request
     * @return void
     */
    public function separateItem(OrderExits $orderExit, Request $request)
    {

        DB::beginTransaction();

        try {
            $response = new Response();

            if (empty($request->addressing_hdn) || $request->addressing_hdn != $request->addressing) {
                echo (new Response())->error('Verifique o Endereçamento')->json();
                return;
            }

            if (empty($request->isbn_hdn) || $request->isbn_hdn != $request->isbn) {
                echo (new Response())->error('Verifique o ISBN')->json();
                return;
            }

            /* Busca o Item e Atualiza os dados */
            $orderItem = OrderItemExits::where('order_id', $orderExit->id)->where('isbn', $request->isbn)->first();
            $orderItem->separated += (empty($request->quantity) ? 1 : $request->quantity);
            if ($orderItem->separated == $orderItem->quantity) {
                $orderItem->status = $orderItem->status(StateOrderExitItemSeparate::class);
                $response->action('closeModal','modal-default');
            }

            if(!$orderItem->save()){
                echo (new Response())->error('Ops, falha ao separar o item')->json();
                return;
            }

            /* Da Baixa no Endereçamento */
            $stock = Stocks::find($request->stock_id);
            $stock->quantity -= 1;
            $stock->save();

            /* Grava da Onde Foi Retirado o Item */
            if($orderStock = OrderStocks::where('order_item_id',$orderItem->id)->where('stock_id',$request->stock_id)->first()){
                $orderStock->separate_quantity += 1;
                $orderStock->save();
            } else {
                OrderStocks::create([
                    'partner_id'        => $orderExit->partner_id,
                    'order_id'          => $orderExit->id,
                    'order_item_id'     => $orderItem->id,
                    'stock_id'          => $stock->id,
                    'separate_quantity' => 1
                ]);
            }

            DB::commit();

            echo $response->success('Item Separado com Sucesso')
                ->action('loadForm','form-separate')->data(['isbn'=>''])
                ->action('reloadDataTable','item-table')
                ->json();
        } catch (\Exception $exception){
            DB::rollBack();
            echo $response->error("Ops... Não conseguimos separar o item <br> {$exception->getMessage()}")->json();
        }
    }

    /**
     * ENVIA PARA CONFERENCIA
     * @param OrderExits $orderExit
     * @return void
     */
    public function sendToConference(OrderExits $orderExit)
    {
        $stateOrderExit = $orderExit->handle('sendToConference');
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())
            ->action('loadForm', 'myForm')
            ->action('reloadDataTable', 'item-table')
            ->data($stateOrderExit->getOrderExit()->toArray())->json();
    }

    /**
     * RETORNA TODAS AS POSIÇÕES DE ESTOQUE DO ITEM
     * @param OrderExits $orderExit
     * @param OrderItemExits $orderItem
     * @return Collection
     */
    private function getStocks(OrderExits $orderExit, OrderItemExits $orderItem): Collection {
        return Stocks::where('stocks.office_id', $orderExit->office_id)
            ->where('partner_id', $orderExit->partner_id)
            ->where('product_id', $orderItem->product_id)
            ->where('type', 'normal')
            ->get();
    }

}
