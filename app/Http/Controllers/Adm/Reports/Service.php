<?php

namespace App\Http\Controllers\Adm\Reports;

use App\Http\Libraries\Response;
use App\Models\Partners;
use App\Models\Services;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderServices;

class Service extends Controller {

    public function index() {
        $dropPartners = Partners::get(['id','name'])->toArray();
        $dropServices = Services::get(['id','description'])->toArray();
        return view('adm.reports.services',compact('dropPartners','dropServices'));
    }

    public function getServices() {

        $orderServices = OrderServices::with(['order','service'])
        ->whereDate('created_at', '>=', (empty(session('filter_start_date')) ? date('Y-m-01 00:00:00') : session('filter_start_date')." 00:00:00") )
        ->whereDate('created_at', '<=', (empty(session('filter_end_date')) ? date('Y-m-t 00:00:00') : session('filter_end_date')." 23:59:59") );
        if(!empty(session('service_id'))) { $orderServices->where('service_id', session('service_id')); }
        if(!empty(session('partner_id'))) {
            $orderServices->whereHas('order', function($q) {
                $q->where('partner_id', session('partner_id'));
            });
        }

        foreach ($orderServices->get() as $orderService) {
            $data['data'][] = [
                date_fmt($orderService->created_at),
                str_convert_to_document($orderService->order->partner->document01),
                $orderService->order->partner->name,
                $orderService->order->id,
                $orderService->service->description,
                $orderService->quantity,
                money($orderService->price)
            ];
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

        if(!empty($request->partner_id)){
            session(['partner_id'=>$request->partner_id]);
        }

        if(!empty($request->service_id)){
            session(['service_id'=>$request->service_id]);
        }

        echo (new Response())->success('Filtros Aplicados com Sucesso')->action('reloadDataTable','table')->json();

    }

    public function clearFilter() {

        session()->forget('filter_start_date');
        session()->forget('filter_end_date');
        session()->forget('partner_id');
        session()->forget('service_id');
        echo (new Response())->success('Filtros removidos com sucesso')->action('reloadDataTable','table')->json();

    }

}
