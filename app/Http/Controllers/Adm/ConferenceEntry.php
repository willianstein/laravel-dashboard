<?php

namespace App\Http\Controllers\Adm;

use App\Http\Requests\AdmConferenceServiceRequest;
use App\Models\OrderServices;
use App\Models\PartnerServices;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Libraries\Response;
use App\Http\Controllers\Controller;

use App\State\Order\StateOrderEntryConference;

use App\Models\OrderEntries;
use App\Models\OrderItemEntries;
use Illuminate\Support\Facades\Auth;

class ConferenceEntry extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:conferencia-entrada|ver-conferencia-entrada|editar-conferencia-entrada|',
            ['only' => ['index', 'getListOrders', 'manager', 'getListOrderItems', 'checkItem', 'discardItem', 'checked']]
        );
    }

    /**
     * PAGINA INICIAL
     */
    public function index()
    {
        return view('adm.conferenceEntry');
    }

    /**
     * RETORNA LISTA DE PEDIDOS EM JSON
     * @return void
     * @throws \Exception
     */
    public function getListOrders() {
        if($orderEntries = OrderEntries::
            whereIn('type',[OrderEntries::type('Entrada'),OrderEntries::type('Reversa')])
            ->where('status',OrderEntries::status(StateOrderEntryConference::class))
            ->orderBy('id','desc')
            ->get()){
            
            $user = Auth::user();
            foreach ($orderEntries as $orderEntry){
           
                $data['data'][] = [
                    date_fmt($orderEntry->created_at, 'd/m/Y H:m'),
                    $orderEntry->id,
                    $orderEntry->third_system_id,
                    $orderEntry->office->name,
                    $orderEntry->partner->name,
                    $orderEntry->status,
                    date_fmt($orderEntry->forecast, 'd/m/Y'),
                    $user->hasPermissionTo('editar-conferencia-entrada') ?
                    "<a href=\"" . route('adm.conferenceEntry.manager', ['orderEntry' => $orderEntry->id]) . "\" class=\"badge badge-success\"><i class=\"fas fa-pen\"></i> EDITAR</a>"
                    : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * PAGINA DE MANUTENÇÃO DA CONFERÊNCIA
     * @param OrderEntries $orderEntry
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function manager(OrderEntries $orderEntry) {
        return view('adm.conferenceEntryManager', compact('orderEntry'));
    }

    /**
     * RETORNA LISTA DOS ITENS DO PEDIDO EM JSON
     * @param OrderEntries $orderEntry
     * @return void
     */
    public function getListOrderItems(OrderEntries $orderEntry)
    {
        $user = Auth::user();
        if (!empty($orderEntry->items->toArray())) {
            foreach ($orderEntry->items as $orderItem) {

                $vercapa = $user->hasPermissionTo('ver-capa-conferencia-entrada') ?
                    "<a href=\"" . Storage::disk('public')->url($orderItem->product->cover) . "\" data-fancybox=\"image\" class=\"badge badge-info text-bold mr-2 fancybox\"><i class=\"fas fa-eye\"></i> VER CAPA</a>"
                    : '';

                $descartar = $user->hasPermissionTo('descartar-conferencia-entrada') ?
                    "<span data-id=\"{$orderItem->id}\" data-product=\"{$orderItem->product_id}\" data-partner=\"$orderEntry->partner_id\" data-toggle=\"modal\" data-target=\"#modal-discard\" class=\"badge badge-danger pointer text-bold mr-2 btn-discard\"><i class=\"fas fa-ban\"></i> DESCARTAR</span>"
                    : '';

                $enderecar =  $user->hasPermissionTo('enderecar-conferencia-entrada') ?
                    "<span data-id=\"{$orderItem->id}\" data-product=\"{$orderItem->product_id}\" data-partner=\"$orderEntry->partner_id\" data-toggle=\"modal\" data-target=\"#modal-check\" class=\"badge badge-success pointer text-bold btn-receive\"><i class=\"fas fa-check\"></i> ENDEREÇAR</span>"
                    : '';

                $data['data'][] = [
                    $orderItem->product->isbn,
                    $orderItem->product->title,
                    $orderItem->product->publisher,
                    $orderItem->quantity,
                    ($orderItem->checked ?? 0),
                    ($orderItem->discarded ?? 0),
                    $orderItem->status,
                    "<p class=\"text-right m-0 p-0\">" .
                        $vercapa .
                        $descartar .
                        $enderecar.
                    "</p>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * CONFERE E ENDEREÇA OS ITENS DO PEDIDO
     * @param OrderEntries $orderEntry
     * @param OrderItemEntries $orderItem
     * @param Request $request
     * @return void
     */
    public function checkItem(OrderEntries $orderEntry, OrderItemEntries $orderItem, Request $request)
    {
        $stateOrderItem = $orderItem->handle('checkItem', $request);
        if ($stateOrderItem->isFail()) {
            echo (new Response())->error($stateOrderItem->getMessage())->json();
            return;
        }
        echo (new Response())->success("{$request->quantity} itens processados com sucesso.")
            ->action('reloadDataTable', 'item-table')->json();
    }

    /**
     * DESCARTA E ENDEREÇA OS ITENS DO PEDIDO
     * @param OrderEntries $orderEntry
     * @param OrderItemEntries $orderItem
     * @param Request $request
     * @return void
     */
    public function discardItem(OrderEntries $orderEntry, OrderItemEntries $orderItem, Request $request)
    {
        $stateOrderItem = $orderItem->handle('discardItem', $request);
        if ($stateOrderItem->isFail()) {
            echo (new Response())->error($stateOrderItem->getMessage())->json();
            return;
        }
        echo (new Response())->success("{$request->quantity} itens processados com sucesso.")
            ->action('reloadDataTable', 'item-table')->json();
    }

    /**
     * ADICIONA UM SERVIÇO A CONFERENCIA
     * @param OrderEntries $orderEntry
     * @param AdmConferenceServiceRequest $request
     * @return void
     */
    public function addService(OrderEntries $orderEntry, AdmConferenceServiceRequest $request) {
        $listPost = (object) $request->validated();
        $listPost->price = (new PartnerServices)->currentPrice($orderEntry->partner->id,$listPost->service_id);

        $serviceItem = OrderServices::where('service_id',$listPost->service_id)->where('order_id',$orderEntry->id)->first();
        if(empty($serviceItem)){
            $listPost->order_id = $orderEntry->id;
            if(!OrderServices::create((array)$listPost)){
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

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable','service-table')->json();
    }

    /**
     * LISTA OS SERVIÇOS DA CONFERENCIA
     * @param OrderEntries $orderEntry
     * @return void
     */
    public function getServices(OrderEntries $orderEntry) {
        if(!empty($orderEntry->services->toArray())){
            foreach ($orderEntry->services as $orderService) {
                $data['data'][] = [
                    $orderService->quantity,
                    $orderService->service->description,
                    //view('adm.snippets.order-package-list-buttons', compact('orderService'))->render()
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * CONCLUI A CONFERÊNCIA DO PEDIDO
     * @param OrderEntries $orderEntry
     * @return void
     */
    public function checked(OrderEntries $orderEntry)
    {
        $stateOrderEntry = $orderEntry->handle('checked');
        if ($stateOrderEntry->isFail()) {
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('loadForm', 'myForm')->data($stateOrderEntry->getOrderEntry()->toArray())->json();
    }
}
