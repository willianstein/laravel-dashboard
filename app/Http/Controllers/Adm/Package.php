<?php

namespace App\Http\Controllers\Adm;

use App\Http\Libraries\Response;
use App\Http\Requests\AdmPackageRequest;
use App\Models\Packages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 *  GESTÃO DE PACOTES
 */
class Package extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:pacote|cadastrar-pacote|editar-pacote|inativar-pacote|ver-pacote',
            ['only' => ['index', 'getPackage', 'getPackages']]
        );
    }
    /**
     * PÁGINA PRINCIPAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('adm.package');
    }

    public function save(AdmPackageRequest $request)
    {
        $listPost = $request->validated();

        if (!Packages::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    /**
     * RETORNA LISTA DE PACOTES EM JSON
     * @return void
     */
    public function getPackages()
    {
        $user = Auth::user();
        if ($packages = Packages::all()) {
            foreach ($packages as $package) {
                /* Botão Ativo */
                $switchActive   = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                    . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"" . route('adm.package.onOff', ['package' => $package->id]) . "\"  id=\"active_contact_{$package->id}\" " . (empty($package->active) ? "" : "checked") . ">\n"
                    . "    <label class=\"custom-control-label\" for=\"active_contact_{$package->id}\">Ativo?</label>\n"
                    . "</div>";

                $data['data'][] = [
                    $package->name,
                    $user->hasPermissionTo('inativar-pacote') ? $switchActive : '',
                    $user->hasPermissionTo('editar-pacote') ?
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"myForm\" data-url=\"" . route('adm.package.getPackage', ['package' => $package->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * RETORNA UM PACOTE PELO ID EM JSON
     * @param Packages $package
     * @return void
     */
    public function getPackage(Packages $package)
    {
        echo (new Response())->action('loadForm', 'myForm')->data($package->toArray())->json();
    }
}
