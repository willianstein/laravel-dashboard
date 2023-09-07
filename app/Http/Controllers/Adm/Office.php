<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmOfficeRequest;
use App\Models\Offices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 *  CADASTRO DE UNIDADES
 */
class Office extends Controller {

    function __construct()
    {
        $this->middleware('permission:unidades|cadastrar-unidades|inativar-unidades|editar-unidades|deletar-unidades',
                            ['only' => ['index','save','getOffices', 'getOffice', 'onOff']]
                         );
    }


    /**
     * PAGINA INICIAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
        // dd("as");
        return view('adm.office');
    }

    /**
     * SALVA UNIDADE NO BANCO DE DADOS
     * @param AdmOfficeRequest $request
     * @return void
     */
    public function save(AdmOfficeRequest $request) {

        $listPost = $request->validated();

        if(!Offices::updateOrCreate(['id'=>($listPost['id']??0)],$listPost)->save()){
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable','table')->json();

    }

    /**
     * LISTA TODAS AS UNIDADES E DEVOLVE EM JSON
     * @return void
     */
    public function getOffices() {

        /* Busca todos os Offices */
        $user = Auth::user();

        if($offices = Offices::all()){
            foreach ($offices as $office){
                /* Botão Ativo */
                $switchActive = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                              . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"".route('adm.office.onOff',['office'=>$office->id])."\"  id=\"active_contact_{$office->id}\" ".(empty($office->active)?"":"checked").">\n"
                              . "    <label class=\"custom-control-label\" for=\"active_contact_{$office->id}\">Ativo?</label>\n"
                              . "</div>";

                $data['data'][] = [
                    $office->name,
                    $user->hasPermissionTo('inativar-unidades') ? $switchActive : "",
                    $user->hasPermissionTo('editar-unidades') ?
                     "<span class=\"badge badge-success ajax-link\" data-obj=\"myForm\" data-url=\"".route('adm.office.getOffice',['office'=>$office->id])."\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : "",
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * LISTA UNIDADE POR ID E RETORNA EM JSON
     * @param Offices $office
     * @return void
     */
    public function getOffice(Offices $office) {

        echo (new Response())->action('loadForm','myForm')->data($office->toArray())->json();
    }

    /**
     * ATIVA OU DESATIVA UM REGISTRO
     * @param Offices $office
     * @return void
     */
    public function onOff(Offices $office) {
        if($office){
            $office->active = ($office->active == "1" ? 0 : 1);
            if($office->save()){
                echo (new Response())->success('Alteração Salva com Sucesso')->json();
            } else {
                echo (new Response())->error('Falha ao Salvar Registro')->json();
            }
        }
    }
}
