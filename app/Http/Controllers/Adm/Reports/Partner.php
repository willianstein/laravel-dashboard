<?php

namespace App\Http\Controllers\Adm\Reports;

use App\Http\Controllers\Controller;
use App\Models\Partners;
use Illuminate\Http\Request;

class Partner extends Controller {

    public function index() {
        return view('adm.reports.partner');
    }

    public function getPartners() {
        /* Busca todos os Offices */
        if($partners = Partners::all()){
            foreach ($partners as $partner){
                $data['data'][] = [
                    str_convert_to_document($partner->document01),
                    $partner->name,
                    $partner->trade_name,
                    str_convert_to_phone($partner->phone),
                    $partner->email,
                    "{$partner->address[0]->address}, {$partner->address[0]->number} {$partner->address[0]->complement}",
                    $partner->address[0]->neighborhood,
                    $partner->address[0]->city,
                    $partner->address[0]->state,
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

}
