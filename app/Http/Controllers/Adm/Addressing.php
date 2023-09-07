<?php

namespace App\Http\Controllers\Adm;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmAddressingRequest;
use App\Models\Addressings;
use App\Models\Offices;
use Illuminate\Support\Facades\Auth;

/**
 *  CADASTRO DE ENDEREÇAMENTO
 */
class Addressing extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:enderecamentos|cadastrar-enderecamentos|ver-enderecamentos|inativar-enderecamentos|imprimir-etiqueta-enderecamento',
            ['only' => ['index', 'save', 'getAddressings', 'getAddressing', 'selectTags','printTags']]
        );
    }
    /**
     * PAGINA INICIAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $offices = Offices::all();
        return view('adm.addressing', compact('offices'));
    }

    /**
     * RECUPERA LISTA DE ENDEREÇAMENTOS
     * @return void
     */
    public function getAddressings()
    {
        /* Busca todos os endereçamentos */
        $user = Auth::user();
        if ($addressings = Addressings::all()) {
            foreach ($addressings as $addressing) {
                /* Botão Ativo */
                $switchActive = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                    . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"" . route('adm.addressing.onOff', ['addressing' => $addressing->id]) . "\"  id=\"active_contact_{$addressing->id}\" " . (empty($addressing->active) ? "" : "checked") . ">\n"
                    . "    <label class=\"custom-control-label\" for=\"active_contact_{$addressing->id}\">Ativo?</label>\n"
                    . "</div>";

                $data['data'][] = [
                    $addressing->office->name,
                    $addressing->name,
                    $addressing->distance,
                    $user->hasPermissionTo('inativar-enderecamentos') ? $switchActive : '',
                    $user->hasPermissionTo('editar-enderecamentos') ?
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"myForm\" data-url=\"" . route('adm.addressing.getAddressing', ['addressing' => $addressing->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * RECUPERA ENDEREÇAMENTO POR ID
     * @param Addressings $addressing
     * @return void
     */
    public function getAddressing(Addressings $addressing)
    {
        echo (new Response())->action('loadForm', 'myForm')->data($addressing->toArray())->json();
    }

    /**
     * CADASTRA OU ATUALIZA ENDEREÇAMENTO
     * @param AdmAddressingRequest $request
     * @return void
     */
    public function save(AdmAddressingRequest $request)
    {
        $listPost = $request->validated();

        if (!Addressings::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    public function selectTags()
    {
        $addressings = Addressings::all();
        return view('adm.addressingSelectTags', compact('addressings'));
    }

    public function getTags()
    {
        /* Busca todos os endereçamentos */
        if ($addressings = Addressings::all()) {
            foreach ($addressings as $addressing) {

                $inputCheck = "<input type=\"checkbox\" value=\"{$addressing->name}\" name=\"AD_{$addressing->id}\" id=\"AD_{$addressing->id}\" class=\"checkbox\">";

                $data['data'][] = [
                    $inputCheck,
                    $addressing->name,
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function printTags(Request $request)
    {
        $printable = [];

        if (empty($request->toArray())) {
            (new Response())->info('Dados ausentes')->flash();
            redirect('adm.addressing.selectTags');
        }

        foreach ($request->toArray() as $field => $value) {
            if (substr($field, 0, 3) == "AD_") {
                $printable[] = $value;
            }
        }

        return view('tags.addressing', compact('printable'));
    }
}
