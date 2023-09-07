<?php

namespace App\Http\Controllers\Adm;

use App\Http\Libraries\PrintableTags;
use App\Http\Requests\AdmConferenceServiceRequest;
use App\Models\History\History;
use App\Models\Orders;
use App\Models\OrderServices;
use App\Models\PartnerServices;
use App\State\OrderItems\StateOrderExitItemChecked;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

use App\Http\Requests\AdmOrderAddPackageRequest;
use App\Http\Libraries\Response;
use App\Http\Controllers\Controller;
use App\State\Order\StateOrderExitConference;

use App\Models\OrderExits;
use App\Models\OrderPackages;
use App\Models\Services;
use App\Models\OrderItemExits;
use App\Models\Packages;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ConferenceExit extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:conferencia-entrada|ver-conferencia-entrada|editar-conferencia-entrada|',
            ['only' => ['index', 'getListOrders', 'manager', 'getListOrderItems', 'checkItem',
            'addPackage', 'checked','addService','getServices','getPackages','printTag']]
        );
    }

    public function index()
    {
        return view('adm.conferenceExit');
    }

    /**
     * RETORNA LISTA DE PEDIDOS EM JSON
     * @return void
     * @throws \Exception
     */
    public function getListOrders() {
        if($orderExits = OrderExits::where('type',orderExits::type('Saída'))
            ->where('status',orderExits::status(StateorderExitConference::class))
            ->orderBy('id','desc')
            ->get()){

            $user = Auth::user();
            foreach ($orderExits as $orderExit){

                $data['data'][] = [
                    date_fmt($orderExit->created_at, 'd/m/Y H:m'),
                    $orderExit->office->name,
                    $orderExit->id,
                    $orderExit->third_system_id,
                    $orderExit->partner->name,
                    $orderExit->status,
                    date_fmt($orderExit->forecast, 'd/m/Y'),
                    $user->hasPermissionTo('detalhes-conferencia-saida') ?
                    "<a href=\"" . route('adm.conferenceExit.manager', ['orderExit' => $orderExit->id]) . "\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> DETALHES</a>"
                    : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * PAGINA DE MANUTENÇÃO DA CONFERÊNCIA
     * @param OrderExits $orderExit
     * @return Application|Factory|View
     */
    public function manager(OrderExits $orderExit) {
        $dropPackages = Packages::get(['id','name'])->toArray();
        $dropServices = Services::get(['id','description'])->toArray();
        $dropSupervisors = User::whereIn('id',Orders::SUPERVISORSIDS)->get(['id','name'])->toArray();
        $dropPackageOrigins = OrderPackages::ORIGIN;
        $dropTagTemplates = PrintableTags::TEMPLATES;
        return view('adm.conferenceExitManager', compact('orderExit','dropPackages','dropPackageOrigins','dropServices','dropTagTemplates','dropSupervisors'));
    }

    /**
     * RETORNA LISTA DOS ITENS DO PEDIDO EM JSON
     * @param OrderExits $orderExit
     * @return void
     */
    public function getListOrderItems(OrderExits $orderExit)
    {
        if (!empty($orderExit->items->toArray())) {
            foreach ($orderExit->items as $orderItem) {
                $data['data'][] = [
                    $orderItem->product->isbn,
                    $orderItem->product->title,
                    $orderItem->product->publisher,
                    $orderItem->quantity,
                    ($orderItem->checked ?? 0),
                    $orderItem->status,
                    view('adm.snippets.order-item-entry-buttons', compact('orderItem'))->render()
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * CONFERE UM ITEM PELO ISBN
     * @param OrderExits $orderExit
     * @param Request $request
     * @return void
     */
    public function checkItem(OrderExits $orderExit, Request $request)
    {

        if (!$item = OrderItemExits::where('order_id', $orderExit->id)->where('isbn', $request->isbn)->first()) {
            echo (new Response())->error('Item não encontrado')->json();
            return;
        }

        if ($item->quantity > $item->checked) {
            $item->checked += 1;
            if ($item->quantity == $item->checked) {
                $item->status = OrderItemExits::status(StateOrderExitItemChecked::class);
            }
            $item->save();
        } else {
            echo (new Response())->error("O item {$item->product->title} já esta conferido por completo")->json();
            return;
        }

        echo (new Response())->success('Sucesso')
            ->action('loadForm', 'conferenceItem')->data(['isbn' => ''])
            ->action('reloadDataTable', 'item-table')
            ->json();
    }

    /**
     * CONCLUIR CONFERÊNCIA
     * @param OrderExits $orderExit
     * @return void
     */
    public function checked(OrderExits $orderExit)
    {
        $stateOrderExit = $orderExit->handle('checked');
        if ($stateOrderExit->isFail()) {
            echo (new Response())->error($stateOrderExit->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderExit->getMessage())->action('loadForm', 'myForm')->data($stateOrderExit->getOrderExit()->toArray())->json();
    }



    /**
     * ADICIONA UM PACOTE A CONFERENCIA
     * @param OrderExits $orderExit
     * @param AdmOrderAddPackageRequest $request
     * @return void
     */
    public function addPackage(OrderExits $orderExit, AdmOrderAddPackageRequest $request)
    {
        $listPost = (object) $request->validated();

        $packageItem = OrderPackages::where('package_id', $listPost->package_id)->where('order_id', $orderExit->id)->first();
        if (empty($packageItem)) {
            $listPost->order_id = $orderExit->id;
            if (!OrderPackages::create((array)$listPost)) {
                echo (new Response())->error('Ops, não foi possivel salvar os dados')->json();
                return;
            }
        } else {
            $packageItem->quantity += (int) $listPost->quantity;
            if (!$packageItem->save()) {
                echo (new Response())->error('Ops, não foi possivel atualizar os dados')->json();
                return;
            }
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable', 'package-table')->json();
    }

    public function removePackage(OrderExits $orderExit, OrderPackages $orderPackage) {

        if(!$orderPackage->delete()){
            echo (new Response())->error('Falha ao Remover Volume')->json();
        }

        echo (new Response())->success('Removido com Sucesso')->action('reloadDataTable','package-table')->json();

    }

    /**
     * RETORNA OS PACOTES ADICIONADOS A CONFERENCIA EM JSON
     * @param OrderExits $orderExit
     * @return void
     */
    public function getPackages(OrderExits $orderExit)
    {
        if (!empty($orderExit->packages->toArray())) {
            foreach ($orderExit->packages as $orderPackage) {
                $data['data'][] = [
                    $orderPackage->quantity,
                    $orderPackage->package->name,
                    $orderPackage->origin,
                    "<a href=\"".route('adm.conferenceExit.removePackage',[$orderExit,$orderPackage])."\" class=\"badge badge-info text-bold ajax-link\"><i class=\"fas fa-trash\"></i> Remover</a>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }



    /**
     * ADICIONA UM SERVIÇO A CONFERENCIA
     * @param OrderExits $orderExit
     * @param AdmConferenceServiceRequest $request
     * @return void
     */
    public function addService(OrderExits $orderExit, AdmConferenceServiceRequest $request)
    {
        $listPost = (object) $request->validated();
        $listPost->price = (new PartnerServices)->currentPrice($orderExit->partner->id, $listPost->service_id);

        $serviceItem = OrderServices::where('service_id', $listPost->service_id)->where('order_id', $orderExit->id)->first();
        if (empty($serviceItem)) {
            $listPost->order_id = $orderExit->id;
            if (!OrderServices::create((array)$listPost)) {
                echo (new Response())->error('Ops, não foi possivel salvar os dados')->json();
                return;
            }
        } else {
            $serviceItem->quantity += (int) $listPost->quantity;
            $serviceItem->price = (float) $listPost->price;
            if(!$serviceItem->save()){
                echo (new Response())->error('Ops, não foi possivel atualizar os dados')->json();
                return;
            }
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable', 'service-table')->json();
    }

    /**
     * LISTA OS SERVIÇOS DA CONFERENCIA
     * @param OrderExits $orderExit
     * @return void
     */
    public function getServices(OrderExits $orderExit)
    {
        if (!empty($orderExit->services->toArray())) {
            foreach ($orderExit->services as $orderService) {
                $data['data'][] = [
                    $orderService->quantity,
                    $orderService->service->description,
                    //view('adm.snippets.order-package-list-buttons', compact('orderService'))->render()
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }


    public function printTag(OrderExits $orderExit, Request $request)
    {
        $orderUrl = route('adm.orderExit.manager', $orderExit);
        return view($request->tag, compact('orderExit', 'orderUrl'));
    }


    /**
     * CONFERENCIA EM LOTES
     * @param OrderExits $orderExit
     * @param Request $request
     * @return void
     */
    public function conferenceInLots(OrderExits $orderExit, Request $request) {

        $request->validate([
            'quantity' => 'required|integer',
            'password' => 'required',
        ]);

        $user = User::find((int)$request->supervisor_id);
        if(!Hash::check($request->password,$user->password)){
            echo (new Response())->warning('Ops! a senha esta incorreta')->json();
            return;
        }

        if(!$orderItem = OrderItemExits::where('order_id',$orderExit->id)->where('isbn',$request->isbn)->first()){
            echo (new Response())->warning('Ops! ISBN não localizado')->json();
            return;
        }

        if(((int)$request->quantity + $orderItem->checked) > $orderItem->quantity){
            echo (new Response())->warning('Ops! Quantidade superior ao solicitado')->json();
            return;
        }

        /* Incrementa Quantidade Conferida */
        $orderItem->checked += (int)$request->quantity;
        $orderItem->save();

        /* Histórico */
        (new History($orderExit))
            ->description("O(a) {$user->name} liberou {$request->quantity} itens na conferência em lote do ISBN: {$orderItem->isbn}")
            ->save();

        if($orderItem->checked == $orderItem->quantity){
            $orderItem->status = "Conferido";
            $orderItem->save();
            echo (new Response())->success('Item Conferido com Sucesso')
                ->action('closeModal','modal-conference')
                ->action('reloadDataTable','item-table')->json();
            return;
        }

        echo (new Response())->info("{$request->quantity} item(ns) contabilizado(s)")
            ->action('closeModal','modal-conference')
            ->action('reloadDataTable','item-table')->json();
        return;

    }
}
