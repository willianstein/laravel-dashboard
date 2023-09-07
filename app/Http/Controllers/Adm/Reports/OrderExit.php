<?php

namespace App\Http\Controllers\Adm\Reports;

use Illuminate\Http\Request;
use App\Http\Libraries\Response;
use App\Models\Partners;
use App\Models\OrderItemExits;

class OrderExit {

    public function index() {
        $dropPartners = Partners::get(['id','name'])->toArray();
        return view('adm.reports.orderExit',compact('dropPartners'));
    }

    public function getOrderExits() {

        $fromDate = date(empty(session('filter_start_date'))?"Y-m-01 00:00:00":session('filter_start_date')." 00:00:00");
        $toDate = date(empty(session('filter_end_date'))?"Y-m-01 23:59:59":session('filter_end_date')." 23:59:59");

        $orderItemExits = OrderItemExits::with('order')
            ->with('product')
            ->whereHas('order', function($q) {
                $q->where('type', 'saida');
            })
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate);

        if($orderItemExits = $orderItemExits->get()){
            foreach ($orderItemExits as $orderItemExit){
                $data['data'][] = [
                    date_fmt($orderItemExit->created_at),
                    str_convert_to_document($orderItemExit->order->partner->document01),
                    $orderItemExit->order->partner->name,
                    $orderItemExit->order->id,
                    $orderItemExit->order->third_system_id,
                    $orderItemExit->product->isbn,
                    $orderItemExit->product->title,
                    $orderItemExit->quantity
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    public function setFilter(Request $request) {

        if(!empty($request->start_date)){
            session(['filter_start_date'=>$request->start_date]);
        }

        if(!empty($request->end_date)){
            session(['filter_end_date'=>$request->end_date]);
        }

        echo (new Response())->success('Filtros Aplicados com Sucesso')->action('reloadDataTable','table')->json();

    }

    public function clearFilter() {

        session()->forget('filter_start_date');
        session()->forget('filter_end_date');
        echo (new Response())->success('Filtros removidos com sucesso')->action('reloadDataTable','table')->json();

    }

}
