<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Models\OrderEntries;
use App\State\Order\StateOrderEntryReceive;
use App\State\Order\StateOrderEntryReceived;
use Illuminate\Http\Request;

class ExpeditionReverse extends Controller {
    /**
     * PAGINA INICIAL
     */
    public function index() {
        $reverse = true;
        return view('adm.expeditionEntry',compact('reverse'));
    }

    /**
     * LISTA OS PEDIDOS QUE VAO CHEGAR
     * @return void
     * @throws \Exception
     */
    public function getListOrders() {
        if($orderEntries = OrderEntries::where('type',OrderEntries::type('Reversa'))
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
}
