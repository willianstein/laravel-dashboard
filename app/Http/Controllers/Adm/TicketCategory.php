<?php

namespace App\Http\Controllers\Adm;

use Illuminate\Http\Request;
use App\Http\Libraries\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdmTicketCategoryRequest;
use App\Models\TicketCategories;
use Illuminate\Support\Facades\Auth;

class TicketCategory extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:categoria-ticket|ver-categoria-ticket|cadastrar-categoria-ticket|inativar-categoria-ticket|editar-categoria-ticket|deletar-categoria-ticket',
            ['only' => ['index', 'save', 'getTicketCategories', 'getTicketCategory']]
        );
    }
    /**
     * PAGINA INICIAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('adm.ticketCategory');
    }

    /**
     * SALVA CATEGORIA NO BANCO DE DADOS
     * @param AdmTicketCategoryRequest $request
     * @return void
     */
    public function save(AdmTicketCategoryRequest $request)
    {

        $listPost = $request->validated();

        if (!TicketCategories::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    /**
     * LISTA TODAS AS UNIDADES E DEVOLVE EM JSON
     * @return void
     */
    public function getTicketCategories()
    {
        $user = Auth::user();
        /* Busca todas as categorias */
        if ($ticketCategories = TicketCategories::all()) {
            foreach ($ticketCategories as $ticketCategory) {
                /* Botão Ativo */
                $switchActive = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                    . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"" . route('adm.ticketCategory.onOff', ['ticketCategory' => $ticketCategory->id]) . "\"  id=\"active_contact_{$ticketCategory->id}\" " . (empty($ticketCategory->active) ? "" : "checked") . ">\n"
                    . "    <label class=\"custom-control-label\" for=\"active_contact_{$ticketCategory->id}\">Ativo?</label>\n"
                    . "</div>";

                $data['data'][] = [
                    $ticketCategory->name,
                    $user->hasPermissionTo('inativar-categoria-ticket') ? $switchActive : '',
                    $user->hasPermissionTo('editar-categoria-ticket') ?
                    "<span class=\"badge badge-success ajax-link\" data-obj=\"myForm\" data-url=\"" . route('adm.ticketCategory.getTicketCategory', ['ticketCategory' => $ticketCategory->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * LISTA UNIDADE POR ID E RETORNA EM JSON
     * @param TicketCategories $ticketCategory
     * @return void
     */
    public function getTicketCategory(TicketCategories $ticketCategory)
    {
        echo (new Response())->action('loadForm', 'myForm')->data($ticketCategory->toArray())->json();
    }
}
