<?php

namespace App\Http\Controllers\Adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;

use App\State\Order\StateOrderEntryReceive;
use App\State\Order\StateOrderEntryReceived;

use App\Models\OrderEntries;
use App\Models\OrderItemEntries;
use App\Models\Partners;
use App\Models\Transports;

class ExpeditionEntry extends Controller {

    /**
     * PAGINA INICIAL
     */
    public function index() {
        return view('adm.expeditionEntry');
    }

    /**
     * LISTA OS PEDIDOS QUE VAO CHEGAR
     * @return void
     * @throws \Exception
     */
    public function getListOrders() {
        if($orderEntries = OrderEntries::where('type',OrderEntries::type('Entrada'))
            ->whereIn('status',[
                OrderEntries::status(StateOrderEntryReceive::class),
                OrderEntries::status(StateOrderEntryReceived::class)
            ])
            ->orderBy('id','desc')->get()){
            foreach ($orderEntries as $orderEntry){
                $data['data'][] = [
                    date_fmt($orderEntry->created_at,'d/m/Y H:m'),
                    $orderEntry->id,
                    $orderEntry->third_system_id,
                    $orderEntry->office->name,
                    $orderEntry->partner->name,
                    date_fmt($orderEntry->forecast, 'd/m/Y'),
                    $orderEntry->status,
                    "<a href=\"".route('adm.expeditionEntry.manager',['orderEntry'=>$orderEntry->id])."\" class=\"badge badge-success\"><i class=\"fas fa-inbox\"></i> RECEBER</a>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * RETORNA ITENS DO PEDIDO SELECIONADO
     * @param OrderEntries $orderEntry
     * @return void
     */
    public function getListOrderItems(OrderEntries $orderEntry) {
        if(!empty($orderEntry->items->toArray())){
            foreach ($orderEntry->items as $orderItem) {
                $data['data'][] = [
                    $orderItem->product->title,
                    $orderItem->quantity,
                    $orderItem->status,
                    "<p class=\"text-right m-0 p-0\">".
                    "    <span data-id=\"{$orderItem->id}\" data-toggle=\"modal\" data-target=\"#modal-refuse\" class=\"badge badge-danger pointer text-bold mr-3 btn-rf\"><i class=\"fas fa-ban\"></i> RECUSAR</span>".
                    "    <span data-id=\"{$orderItem->id}\" data-toggle=\"modal\" data-target=\"#modal-receive\" class=\"badge badge-success pointer text-bold btn-rc\"><i class=\"fas fa-check\"></i> RECEBER</span>".
                    "</p>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function manager(OrderEntries $orderEntry) {

        $dropModality = Transports::MODALITY;
        $dropPackaging = Transports::PACKAGING;
        $dropTransport = Partners::get(['id','name'])->toArray();
        return view('adm.expeditionEntryManager', compact('orderEntry','dropModality','dropPackaging','dropTransport'));

    }

    public function received(OrderEntries $orderEntry) {
        $stateOrderEntry = $orderEntry->handle('received');
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('loadForm','myForm')->data($stateOrderEntry->getOrderEntry()->toArray())->json();
    }

    public function sendToCheck(OrderEntries $orderEntry) {
        $stateOrderEntry = $orderEntry->handle('sendToCheck');
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('loadForm','myForm')->data($stateOrderEntry->getOrderEntry()->toArray())->json();
    }

    public function receiveItem(OrderItemEntries $orderItem, Request $request) {
        $stateOrderEntry = $orderItem->handle('receiveItem',$request->toArray());
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('reloadDataTable','item-table')->json();
    }

    public function refuseItem(OrderItemEntries $orderItem, Request $request) {
        $stateOrderEntry = $orderItem->handle('refuseItem',$request->toArray());
        if($stateOrderEntry->isFail()){
            echo (new Response())->error($stateOrderEntry->getMessage())->json();
            return;
        }
        echo (new Response())->success($stateOrderEntry->getMessage())->action('reloadDataTable','item-table')->json();
    }

}
