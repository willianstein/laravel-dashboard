<?php

namespace App\Http\Controllers\Adm\Reports;

use App\Http\Libraries\Response;
use App\Models\Partners;
use App\Models\Products;
use App\Models\Stocks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Stock extends Controller {

    public function index() {
        $dropPartners = Partners::where('type','Cliente')->get(['id','name'])->toArray();
        $dropProducts = Products::get(['id','isbn','title'])->toArray();
        return view('adm.reports.stocks',compact('dropPartners','dropProducts'));
    }

    public function getStocks() {

        $stocks = Stocks::with(['office','partner','product','addressing']);
        if(!empty(session('partner_id'))) { $stocks->where('partner_id', session('partner_id')); }
        if(!empty(session('product_id'))) { $stocks->where('product_id', session('product_id')); }
        if(!empty(session('addressing'))) {
            $stocks->whereHas('addressing', function($q) {
                $q->where('name', 'like', '%'.session('addressing').'%');
            });
        }

        foreach ($stocks->get() as $stock) {
            $data['data'][] = [
                $stock->office->name,
                $stock->partner->name,
                $stock->product->isbn,
                $stock->product->title,
                $stock->addressing->name,
                ucfirst($stock->type),
                $stock->quantity_max,
                $stock->quantity_min,
                $stock->quantity,
            ];
        }

        echo json_encode(($data??['data'=>[]]));
    }

    public function setFilter(Request $request) {

        if(!empty($request->addressing)){
            session(['addressing'=>$request->addressing]);
        }

        if(!empty($request->partner_id)){
            session(['partner_id'=>$request->partner_id]);
        }

        if(!empty($request->product_id)){
            session(['product_id'=>$request->product_id]);
        }

        echo (new Response())->success('Filtros Aplicados com Sucesso')->action('reloadDataTable','table')->json();

    }

    public function clearFilter() {

        session()->forget('addressing');
        session()->forget('partner_id');
        session()->forget('product_id');
        echo (new Response())->success('Filtros removidos com sucesso')
            ->action('reloadDataTable','table')
            ->action('clearForm',true)
            ->json();

    }

}
