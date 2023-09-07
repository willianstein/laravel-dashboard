<?php

namespace App\Http\Controllers\Adm;

use CliqueTI\NfeXmlHandler\Nfe;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmTransportInExpeditionRequest;
use App\Models\History\History;
use App\State\Order\StateOrderExitTransport;
use App\Models\OrderExits;
use App\Models\OrderItemExits;
use App\Models\Partners;
use App\Models\Transports;
use Illuminate\Support\Facades\Auth;
use NFePHP\DA\NFe\Danfe;

/**
 *  EXPEDIÇÃO DO PEDIDO DE SAIDA
 */
class ExpeditionExit extends Controller
{

    /**
     * PÁGINA PRINCIPAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('adm.expeditionExit');
    }

    /**
     * RETORNA LISTA DE PEDIDOS A SEREM EXPEDIDOS
     * @return void
     * @throws \Exception
     */
    public function getListOrders()
    {
        if ($orderExits = OrderExits::where('type', OrderExits::type('Saída'))
            ->where('status', OrderExits::status(StateOrderExitTransport::class))
            ->orderBy('id', 'desc')
            ->get()
        ) {
            foreach ($orderExits as $orderExit) {
                $data['data'][] = [
                    date_fmt($orderExit->created_at, 'd/m/Y H:m'),
                    $orderExit->id,
                    $orderExit->third_system_id,
                    $orderExit->office->name,
                    $orderExit->partner->name,
                    date_fmt($orderExit->forecast, 'd/m/Y'),
                    $orderExit->status,
                    "<a href=\"" . route('adm.expeditionExit.manager', ['orderExit' => $orderExit->id]) . "\" class=\"badge badge-success\"><i class=\"fas fa-eye\"></i> DETALHES</a>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * GESTÃO DA EXPEDIÇÃO DE SAIDA
     * @param OrderExits $orderExit
     * @return Application|Factory|View
     */
    public function manager(OrderExits $orderExit)
    {
        $dropModality = Transports::MODALITY;
        $dropPackaging = Transports::PACKAGING;
        return view('adm.expeditionExitManager', compact('orderExit', 'dropModality', 'dropPackaging'));
    }

    /**
     * CONCLUIR UM PEDIDO
     * @param OrderExits $orderExit
     * @return void
     */
    public function complete(OrderExits $orderExit)
    {
        $stateOrderExit = $orderExit->handle('complete');
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }

        // echo (new Response())->success($stateOrderExit->getMessage())->action('redirect','/roles')->json();

        echo (new Response())->success($stateOrderExit->getMessage())
            ->data($stateOrderExit->getOrderExit()->toArray())
            ->action('redirect', '/expedicao/saida')
            ->json();
    }

    /**
     * RETORNA ITENS DO PEDIDO SELECIONADO
     * @param OrderExits $orderExit
     * @return void
     */
    public function getListOrderItems(OrderExits $orderExit)
    {
        if (!empty($orderExit->items->toArray())) {
            foreach ($orderExit->items as $orderItem) {
                $data['data'][] = [
                    $orderItem->product->title,
                    $orderItem->quantity,
                    $orderItem->status,
                    "<p class=\"text-right m-0 p-0\">" .
                        "    <span data-id=\"{$orderItem->id}\" data-toggle=\"modal\" data-target=\"#modal-dispatch\" class=\"badge badge-success pointer text-bold btn-dispatch\"><i class=\"fas fa-truck-loading\"></i> EMBARCAR</span>" .
                        "</p>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * CONCLUI UM ITEM
     * @param OrderItemExits $orderItem
     * @param Request $request
     * @return void
     */
    public function completeItem(OrderItemExits $orderItem, Request $request)
    {
        $stateOrderExit = $orderItem->handle('completeItem', $request->toArray());
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->action('reloadDataTable', 'item-table')->json();
    }


    /**
     * ATUALIZA TRANSPORTE
     * @param OrderExits $orderExit
     * @param AdmTransportInExpeditionRequest $request
     * @return void
     */
    public function updateTransport(OrderExits $orderExit, AdmTransportInExpeditionRequest $request)
    {
        $stateOrderExit = $orderExit->handle('updateTransport', $request);
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->json();
    }

    /**
     * IMPRIMI ETIQUETA
     * @param OrderExits $orderExit
     * @return Application|Factory|View
     */
    public function printSimpleDanfe(OrderExits $orderExit)
    {

        $nfe = Nfe::conteudoXml(Storage::disk('local')->get($orderExit->invoice));
        return view('tags.simpleDanfe', compact('nfe'));
    }

    /**
     * IMPRIME A DANFE
     * @param OrderExits $orderExit
     * @return Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function printDanfe(OrderExits $orderExit)
    {

        $danfe = new Danfe(Storage::disk('local')->get($orderExit->invoice));
        $danfe->debugMode(true);

        $pdf = $danfe->render();

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }


    public function printDeclaration(OrderExits $orderExit)
    {

        return view('adm.printables.declaration', compact('orderExit'));
    }

    /**
     * RETORNA ITENS DO PEDIDO SELECIONADO
     * @param OrderExits $orderExit
     * @return void
     */
    public function boardingAll(OrderExits $orderExit)
    {
        OrderItemExits::where("order_id", $orderExit->id)->update(['status' => 'Concluído']);

        $user = Auth::user();

        (new History($orderExit))->description("Pedido enbarcado pelo usuário: {$user->name}")->userId("{$user->id}")->save();
        
        $stateOrderExit = $orderExit->handle('complete');
        

        echo (new Response())->success($stateOrderExit->getMessage())
                            ->data($stateOrderExit->getOrderExit()->toArray())
                            ->action('redirect', '/expedicao/saida')
                            ->json();


        // echo (new Response())->success('Embarcado com sucesso')
        //     ->action('redirect', '/expedicao/saida')
        //     ->json();





        // if (!empty($orderExit->items->toArray())) {
        //     foreach ($orderExit->items as $orderItem) {
        //         $data['data'][] = [
        //             $orderItem->product->title,
        //             $orderItem->quantity,
        //             $orderItem->status,
        //             "<p class=\"text-right m-0 p-0\">" .
        //                 "    <span data-id=\"{$orderItem->id}\" data-toggle=\"modal\" data-target=\"#modal-dispatch\" class=\"badge badge-success pointer text-bold btn-dispatch\"><i class=\"fas fa-truck-loading\"></i> EMBARCAR</span>" .
        //                 "</p>"
        //         ];
        //     }
        // }
        // echo json_encode(($data ?? ['data' => []]));
    }
}
