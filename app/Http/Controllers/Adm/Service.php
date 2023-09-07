<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmServiceRequest;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 *  GESTÃO DOS CADASTROS DE SERVIÇOS
 */
class Service extends Controller {

    function __construct()
    {
        $this->middleware('permission:servicos|cadastro-servicos|ver-servicos|inativar-servicos|editar-servicos',
        ['only' => ['index','save','getServices', 'getService']]
     );
    }

    /**
     * PÁGINA PRINCIPAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
        return view('adm.service');
    }

    /**
     * SALVA SERVIÇO
     * @param AdmServiceRequest $request
     * @return void
     */
    public function save(AdmServiceRequest $request) {
        $listPost = $request->validated();

        if(!Services::updateOrCreate(['id'=>($listPost['id']??0)],$listPost)->save()){
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
    public function getServices() {
        /* Busca todos os endereçamentos */
        $user = Auth::user();
        if($services = Services::all()){
            foreach ($services as $service){
                /* Botão Ativo */
                $switchActive   = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                                . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"".route('adm.service.onOff',['service'=>$service->id])."\"  id=\"active_contact_{$service->id}\" ".(empty($service->active)?"":"checked").">\n"
                                . "    <label class=\"custom-control-label\" for=\"active_contact_{$service->id}\">Ativo?</label>\n"
                                . "</div>";

                $data['data'][] = [
                    $service->description,
                    'R$ '.money($service->price),
                    $user->hasPermissionTo('inativar-servicos') ? $switchActive : '',
                    $user->hasPermissionTo('editar-servicos') ?
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"myForm\" data-url=\"".route('adm.service.getService',['service'=>$service->id])."\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : '',
                ];
            }
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * RETORNA UM SERVIÇO POR ID EM JSON
     * @param Services $services
     * @return void
     */
    public function getService(Services $service) {
        echo (new Response())->action('loadForm','myForm')->data($service->toArray())->json();
    }

}
