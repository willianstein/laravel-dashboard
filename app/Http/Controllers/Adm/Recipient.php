<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmRecipientRequest;
use App\Models\Recipients;
use Illuminate\Http\Request;

class Recipient extends Controller {

    public function save(AdmRecipientRequest $request) {
        $listPost = $request->validated();

        if(!$recipient = Recipients::updateOrCreate(['id'=>($listPost['id']??null)],$listPost)){
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->json();

    }

    public function search(Request $request) {
        $term = filter_var($request->term, FILTER_SANITIZE_STRIPPED);
        if($recipient = Recipients::where('document01',$term)->first()){
            echo (new Response())->action('loadForm','myForm')->data($recipient->toArray())->json();
        } else {
            echo (new Response())->info('Destinatário não encontrado')->json();
        }

    }

}
