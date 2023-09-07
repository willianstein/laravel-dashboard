<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmTransportRangeRequest;
use App\Models\TransportRanges;
use Illuminate\Http\Request;

class TransportRange extends Controller {

    public function index() {
        return view('adm.transportRange');
    }

    /**
     * SALVA SERVIÇO
     * @param AdmTransportRangeRequest $request
     * @return void
     */
    public function save(AdmTransportRangeRequest $request) {
        $listPost = $request->validated();

        if(!TransportRanges::updateOrCreate(['id'=>($listPost['id']??0)],$listPost)->save()){
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')
            ->action('clearForm',true)
            ->action('reloadDataTable','table')
            ->json();
    }

    /**
     * RETORNA LISTA DE SERVIÇOS EM JSON
     * @return void
     */
    public function getTransportRanges() {
        /* Busca todos os endereçamentos */
        if($transportRanges = TransportRanges::all()){
            foreach ($transportRanges as $transportRange){

                $data['data'][] = [
                    $transportRange->name,
                    $transportRange->range_from,
                    $transportRange->range_up_to,
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"myForm\" data-url=\"".route('adm.transportRange.getTransportRange',$transportRange)."\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * RETORNA UM SERVIÇO POR ID EM JSON
     * @param TransportRanges $transportRange
     * @return void
     */
    public function getTransportRange(TransportRanges $transportRange) {
        echo (new Response())->action('loadForm','myForm')->data($transportRange->toArray())->json();
    }

}
