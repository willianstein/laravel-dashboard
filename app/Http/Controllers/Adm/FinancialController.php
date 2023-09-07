<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Enums\UserTypeEnum;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmAddressingRequest;
use App\Http\Requests\AdmBank;
use App\Http\Requests\AdmBankRequest;
use App\Http\Requests\AdmBillsRequest;
use App\Http\Requests\AdmBillsRequestReceive;
use App\Http\Requests\AdmBoxRemoveRequest;
use App\Http\Requests\AdmBoxRequest;
use App\Http\Requests\AdmBudgetRequest;
use App\Http\Requests\AdmFinancialRequest;
use App\Models\Addressings;
use App\Models\Bank;
use App\Models\BillsToPay;
use App\Models\BillsToReceive;
use App\Models\Budget;
use App\Models\Financial;
use App\Models\HistoryMovingBox;
use App\Models\MovingBox;
use App\Models\Offices;
use App\Models\PurchaseOrder;
use App\Models\Sectors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use NumberFormatter;
use Spatie\SimpleExcel\SimpleExcelWriter;

class FinancialController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:centro-de-custo|banco|contas-a-pagar|contas-a-receber',
            ['only' => ['index', 'bankIndex', 'getCostCenter', 'save', 'onOffBank', 'getAddressing', 'getBank', 'getBills']]
        );
    }

    /**
     * PAGINA INICIAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $financial = Financial::get();

        return view('finan.financial', compact('financial'));
    }

    public function bankIndex()
    {
        $bank = Bank::get();
        return view('finan.bank', compact('bank'));
    }

    /**
     * RECUPERA LISTA DE ENDEREÇAMENTOS
     * @return void
     */
    public function getCostCenter()
    {
        /* Busca todos os endereçamentos */
        if ($financial = Financial::all()) {
            $user = Auth::user();
            foreach ($financial as $fin) {
                /* Botão Ativo */
                $switchActive = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                    . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"" . route('finan.financial.onOff', ['financial' => $fin->id]) . "\"
                      id=\"active_contact_{$fin->id}\" " . (empty($fin->active) ? "" : "checked") . ">\n"
                    . "    <label class=\"custom-control-label\" for=\"active_contact_{$fin->id}\">Ativo?</label>\n"
                    . "</div>";

                $data['data'][] = [
                    $fin->name,
                    $fin->code,
                    $fin->parent_code,
                    $fin->type,
                    $fin->condition,
                    $user->hasPermissionTo('inativar-centro-de-custo') ? $switchActive : '',
                    $user->hasPermissionTo('editar-centro-de-custo') ?
                        "<span class=\"badge badge-success ajax-link\" data-url=\"" . route('finan.financial.getOrdem', ['orden' => $fin->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                        : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }



    /**
     * ATIVA OU DESATIVA UM REGISTRO
     * @param Financial $office
     * @return void
     */
    public function onOff(Financial $final, $id)
    {
        if ($id) {
            $costCenter = Financial::findOrFail($id);
            $costCenter->active = ($costCenter->active == "1" ? 0 : 1);
            if ($costCenter->save()) {
                echo (new Response())->success('Alteração Salva com Sucesso')->json();
            } else {
                echo (new Response())->error('Falha ao Salvar Registro')->json();
            }
        }
    }

    /**
     * ATIVA OU DESATIVA UM REGISTRO
     * @param Bank $office
     * @return void
     */
    public function onOffBank(Bank $final, $id)
    {
        if ($id) {
            $bank = Bank::findOrFail($id);
            $bank->active = ($bank->active == "1" ? 0 : 1);
            if ($bank->save()) {
                echo (new Response())->success('Alteração Salva com Sucesso')->json();
            } else {
                echo (new Response())->error('Falha ao Salvar Registro')->json();
            }
        }
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
     * @param AdmFinancialRequest $request
     * @return void
     */

    public function save(AdmFinancialRequest $request)
    {
        $listPost = $request->validated();

        if (!Financial::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }
        echo (new Response())->success('Registro Salvo com Sucesso')
            ->action('clearForm', true)->action('reloadDataTable', 'table')->json();
        return;
    }

    /**
     * RECUPERA LISTA DE ENDEREÇAMENTOS
     * @return void
     */
    public function getBank()
    {
        /* Busca todos os endereçamentos */
        // if ($bank = Bank::where('role_id',  UserTypeEnum::CLIENT)->get()) {
        if ($bank = Bank::get()) {
            $user = Auth::user();
            foreach ($bank as $ban) {
                /* Botão Ativo */
                $switchActive = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                    . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"" . route('finan.financial.bank.onOff', ['bank' => $ban->id]) . "\"
                      id=\"active_contact_{$ban->id}\" " . (empty($ban->active) ? "" : "checked") . ">\n"
                    . "    <label class=\"custom-control-label\" for=\"active_contact_{$ban->id}\">Ativo?</label>\n"
                    . "</div>";

                $data['data'][] = [
                    $ban->name,
                    'R$ ' . $ban->balance,
                    $user->hasPermissionTo('inativar-banco') ? $switchActive : '',
                    $user->hasPermissionTo('editar-banco') ?
                        "<span class=\"badge badge-success ajax-link\" data-url=\"" . route('finan.financial.getBank', ['bank' => $ban->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                        : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * CADASTRA OU ATUALIZA ENDEREÇAMENTO
     * @param AdmBankRequest $request
     * @return void
     */

    public function saveBank(AdmBankRequest $request)
    {
        //colocar regra de usuario para mostrar qual typo vai ser salvo no banco
        $listPost = $request->validated();
        $userType = 1;
        //        if (!$listPost['id'] &&  $userType == UserTypeEnum::CLIENT) {
        //            $listPost['role_id'] = 1;
        //        } else {
        //            $listPost['role_id'] = 1;
        //        }
        $listPost['role_id'] = 1;

        $listPost['create_by_id'] = 1;

        $listPost['balance'] = floatval($request->balance);

        if (!Bank::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')
            ->action('clearForm', true)->action('reloadDataTable', 'table')->json();
        return;
    }


    /**
     * Contas a Pagar
     */

    public function indexBillsToPay()
    {
        $bills = BillsToPay::get();
        return view('finan.billsToPay', compact('bills'));
    }

    /**
     * CADASTRA OU ATUALIZA ENDEREÇAMENTO
     * @param AdmBillsRequest $request
     * @return void
     */

    public function saveBills(AdmBillsRequest $request)
    {

        $listPost = $request->validated();
        $listPost['date_competence'] = $request->date_competence ?: date('Y-m-d');
        $listPost['balance'] = floatval($request->value);


        if (!BillsToPay::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')
            ->action('loadForm', true)->action('reloadDataTable', 'table')->json();
        return;
    }


    public function getBills()
    {
        /* Busca todos os endereçamentos */
        if ($bills = BillsToPay::where('status', '<>', 'Baixado')->with(['bank', 'partner', 'partner'])->get()) {
            $user = Auth::user();
            foreach ($bills as $bill) {
                $editar = $user->hasPermissionTo('editar-contas-a-pagar') ?
                    "<span class=\"badge badge-success ajax-link\" data-url=\"" . route('financial.getConta', ['conta' => $bill->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : '';

                $baixar = $user->hasPermissionTo('baixar-contas-a-pagar') ?
                    "<span class=\"badge  badge-danger  ml-1 ajax-link\" data-url=\"" . route('financial.baixarConta', ['conta' => $bill->id]) . "\"><i class=\"fas fa-pen\"></i> BAIXAR</span>"
                    : '';

                $data['data'][] = [
                    $bill->description,
                    'R$ ' . $bill->value,
                    date('m/Y', strtotime($bill->date_competence)),
                    date('d/m/Y', strtotime($bill->date_expire)),
                    $bill->billsToPay->name,
                    $bill->bank->name,
                    $bill->repetition,
                    $bill->partner->name ?? '',
                    // $bill->status,
                    $bill->status == 'baixada' ? "<span class=\"badge badge-danger \" ><i></i>$bill->status</span>" : "<span class=\"badge badge-success \" ><i></i>$bill->status</span>",
                    $bill->status == 'baixada' ?
                        "" :
                        $editar .
                        $baixar

                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }


    /**
     * Busca Centro de custo
     * @param Request $request
     * @return void
     */
    public function findCostCenter(Request $request)
    {
        if ($costCenters = Financial::where('active', true)
            ->where('type', 'analitico')
            ->where('name', 'LIKE', "%{$request->term}%")
            ->orWhere('code', 'LIKE', "%{$request->term}%")
            ->get()
        ) {
            foreach ($costCenters as $costCenter) {
                $return[] = [
                    'id' => $costCenter->id,
                    'text' => $costCenter->name
                ];
            }
        }
        echo json_encode($return ?? null);
    }

    /**
     * Busca Centro de custo
     * @param Request $request
     * @return void
     */
    public function findCostCenterSint(Request $request)
    {
        if ($costCenters = Financial::where('active', true)
            ->where('type', 'analitico')
            ->where('name', 'LIKE', "%{$request->term}%")
            ->orWhere('code', 'LIKE', "%{$request->term}%")
            ->get()
        ) {
            foreach ($costCenters as $costCenter) {
                $return[] = [
                    'id' => $costCenter->id,
                    'text' => $costCenter->name
                ];
            }
        }
        echo json_encode($return ?? null);
    }

    /**
     * Busca Banco
     * @param Request $request
     * @return void
     */
    public function findBank(Request $request)
    {
        if ($banks = Bank::where('active', true)->where('name', 'LIKE', "%{$request->term}%")->get()) {
            foreach ($banks as $bank) {
                $return[] = [
                    'id' => $bank->id,
                    'text' => $bank->name
                ];
            }
        }
        echo json_encode($return ?? null);
    }

    public function getOrdem(Financial $ordem, $idOrdem)
    {
        $ordem = Financial::findOrFail($idOrdem);
        echo (new Response())->action('loadForm', 'formOrdem')->data($ordem->toArray())->json();
    }

    public function getIdBank(Bank $idbank, $getBank)
    {
        $idBank = Bank::findOrFail($getBank);
        echo (new Response())->action('loadForm', 'formBank')->data($idBank->toArray())->json();
    }

    public function getConta(BillsToPay $idBill, $bill)
    {
        $idBill = BillsToPay::where('id', $bill)->with('bank')->first();

        echo (new Response())->action('loadForm', 'myForm')->data($idBill->toArray())->json();
    }


    public function getContaReceive(BillsToReceive $idBill, $bill)
    {
        $idBill = BillsToReceive::select(
            'id',
            'description',
            'value',
            'date_competence',
            'id_cost_center',
            'id_bank',
            'id_favored',
            'repetition',
            'status',
            'date_received'
        )->findOrFail($bill);


        echo (new Response())->action('loadForm', 'myForm')->data($idBill->toArray())->json();
    }

    public function baixarConta(BillsToPay $idBill, $bill)
    {
        $idBill         = BillsToPay::findOrFail($bill);
        $idBill->status = 'baixada';
        $idBill->save();

        echo (new Response())->success('Registro Baixado com Sucesso')
            ->action('loadForm', true)->action('reloadDataTable', 'table')->json();
        return;

        // echo (new Response())->action('loadForm', 'myForm')->json();
    }


    public function getBudget(Budget $idBill, $bill)
    {
        $idBill = Budget::findOrFail($bill);
        echo (new Response())->action('loadForm', 'formBudget')->data($idBill->toArray())->json();
    }

    public function generateOrdem(Budget $idBill, $bill)
    {
        $idBill = Budget::findOrFail($bill);
        $idBill->purchase_order = true;
        $idBill->save();

        $Order                = new PurchaseOrder;
        $Order->budget_number = $idBill->id;
        $Order->objective     = $idBill->objective;
        $Order->value         = $idBill->value;
        $Order->save();


        echo (new Response())->success('Ordem de Compra Gerada com Sucesso')
            ->action('clearForm', true)->action('reloadDataTable', 'table')->json();
        return;

        // echo (new Response())->action('loadForm', 'myForm')->json();
    }


    /**
     * Contas a receber
     */
    public function indexBillsToReceive()
    {
        $bills = BillsToReceive::get();
        return view('finan.billsToReceive', compact('bills'));
    }

    public function getToReceive()
    {
        /* Busca todos os endereçamentos */
        if ($bills = BillsToReceive::with(['bank', 'billsToPay', 'partner'])->get()) {
            $user = Auth::user();

            foreach ($bills as $bill) {

                $editar = $user->hasPermissionTo('editar-contas-a-pagar') ?
                    "<span class=\"badge badge-success ajax-link\" data-url=\"" . route('financial.getConta', ['conta' => $bill->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : '';

                $baixar = $user->hasPermissionTo('baixar-contas-a-pagar') ?
                    "<span class=\"badge  badge-danger  ml-1 ajax-link\" data-url=\"" . route('financial.baixarConta', ['conta' => $bill->id]) . "\"><i class=\"fas fa-pen\"></i> BAIXAR</span>"
                    : '';

                $data['data'][] = [
                    $bill->description,
                    'R$ ' . $bill->value,
                    date('m/Y', strtotime($bill->date_competence)),
                    date('d/m/Y', strtotime($bill->date_received)),
                    $bill->billsToPay->name,
                    $bill->bank->name,
                    $bill->repetition,
                    $bill->partner->name ?? '',
                    $bill->status == 'baixada' ? "<span class=\"badge badge-danger \" ><i></i>$bill->status</span>" : "<span class=\"badge badge-success \" ><i></i>$bill->status</span>",
                    $bill->status == 'baixada' ?
                        "" :
                        $editar .
                        $baixar
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function baixarContaRece(BillsToReceive $idBill, $bill)
    {
        $idBill         = BillsToReceive::findOrFail($bill);
        $idBill->status = 'baixada';
        $idBill->save();

        echo (new Response())->success('Registro Baixado com Sucesso')
            ->action('loadForm', true)->action('reloadDataTable', 'table')->json();
        return;

        // echo (new Response())->action('loadForm', 'myForm')->json();
    }



    /**
     * CADASTRA OU ATUALIZA ENDEREÇAMENTO
     * @param AdmBillsRequest $request
     * @return void
     */

    public function saveBillsReceive(AdmBillsRequestReceive $request)
    {
        $listPost = $request->validated();
        $listPost['date_competence'] = $request->date_competence ?: date('Y-m-d');

        if (!BillsToReceive::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')
            ->action('loadForm', true)->action('reloadDataTable', 'table')->json();
        return;
    }

    /**
     * Relatorio
     */
    public function indexReport()
    {
        $results = null;
        return view('finan.report', compact('results'));
    }

    public function saveReport(Request $request)
    {
        $table = $request->table;
        $reports = DB::table($request->table)
            ->select(
                $request->table . '.description as descricao',
                $request->table . '.value as valor',
                $request->table . '.date_competence as data_de_competencia',
                $request->table . '.repetition as repeticao',
                $request->table . '.status as status',
                'bank.name as nome_banco',
                'bank.balance as saldo_banco',
                'cost_center.code as codigo_centro_de_custo',
                'cost_center.parent_code as codigo_pai',
                'cost_center.name as centro_de_custo',
                'cost_center.type as tipo_centro_de_custo',
                'cost_center.condition as codicao',
                'partners.name as nome_favorecido'
            )
            ->when($request->dateInit, function ($query, $request) use ($table) {
                $query->where($table . '.created_at', '>', $request . ' 00:00:00');
            })->when($request->dateEnd, function ($query, $request) use ($table) {
                $query->where($table . '.created_at', '<', $request);
            })->when($request->id_cost_center, function ($query, $request) {
                $query->where('id_cost_center', '=', $request);
            })->when($request->partner_id, function ($query, $request) {
                $query->where('id_favored', '=', $request);
            })->when($request->id_bank, function ($query, $request) {
                $query->where('id_bank', '=', $request);
            })->when($request->status, function ($query, $request) {
                if ($request != 'null')
                    $query->where('status', '=', $request);
            })
            ->join('bank', 'bank.id', 'id_bank')
            ->join('cost_center', 'cost_center.id', 'id_cost_center')
            ->join('partners', 'partners.id', 'id_favored')
            ->get();


        if ($request->action  == 'download') {
            $writer = SimpleExcelWriter::streamDownload('relatorios.xlsx')
                ->addRows(json_decode(json_encode($reports), true));
            return;
        }

        return view('finan.report', compact('reports'));
    }

    /**
     * orcamentos
     */
    public function indexBudget()
    {
        return view('finan.budget');
    }

    public function getToBudget()
    {
        /* Busca todos os endereçamentos */
        if ($budget = Budget::with(['partner'])->get()) {

            foreach ($budget as $bud) {
                $data['data'][] = [

                    $bud->objective,
                    $bud->start ? date('d/m/Y', strtotime($bud->start)) : '',
                    $bud->end ?  date('d/m/Y', strtotime($bud->end)) : '',
                    date('d/m/Y', strtotime($bud->date_conclusion)),
                    $bud->partner->name,
                    $bud->value,
                    $bud->status == 'Aprovado' ? "<span class=\"badge badge-success \" ><i></i>$bud->status</span>" : "<span class=\"badge badge-danger \" ><i></i>$bud->status</span>",
                    $bud->pdf ? "<a href=\"" . url($bud->pdf) . "\" data-fancybox=\"image\" class=\"badge badge-primary ml-3 fancybox\"><i class=\"far fa-file-image\"></i> Anexo</a>" : "",
                    $bud->status == 'Em Análise' ?
                        "<span class=\"badge badge-success ajax-link\" data-url=\"" . route('budget.aproveBudget', ['id' => $bud->id]) . "\"><i class=\"fas fa-pen\"></i> Aprovar</span>" .
                        "<span class=\"badge badge-danger ml-2 ajax-link\" data-url=\"" . route('budget.reproveBudget', ['id' => $bud->id]) . "\"><i class=\"fas fa-pen\"></i> Reprovar</span>" .
                        "<span class=\"badge badge-success ml-2 ajax-link\" data-url=\"" . route('financial.getBudget', ['id' => $bud->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"  : '',

                    ($bud->status == 'Aprovado' || $bud->purchase_order) ?  "<span class=\"badge badge-success ml-2 ajax-link\" data-url=\"" . route('budget.generateOrdem', ['id' => $bud->id]) . "\"><i class=\"fas fa-location-arrow\"></i> Gerar Ordem</span>"
                        : " "

                    // ($bud->status == 'Aprovado' || $bud->purchase_order) ? '' :
                    //     "<span class=\"badge badge-success ml-2 ajax-link\" data-url=\"" . route('budget.generateOrdem', ['id' => $bud->id]) . "\"><i class=\"fas fa-location-arrow\"></i> Gerar Ordem</span>"

                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function saveToBudget(AdmBudgetRequest $request)
    {
        $listPost = $request->validated();

        if ($request->pdf) {

            $fileName = time() . '.' . $request->pdf->extension();

            $caminho = $request->pdf->move(public_path('uploads'), $fileName);

            $listPost['pdf'] =  'uploads/' . $fileName;
        }

        if (!Budget::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        echo (new Response())->success('Registro Salvo com Sucesso')
            ->action('clearForm', true)->action('reloadDataTable', 'table')->json();
        return;
    }


    public function aproveBudget(Request $request, $id)
    {
        $budget = Budget::findorFail($id);
        $budget->status = 'Aprovado';
        $budget->save();

        echo (new Response())->success('Registro Alterado com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    public function reproveBudget(Request $request, $id)
    {
        $budget = Budget::findorFail($id);
        $budget->status = 'Reprovado';
        $budget->save();

        echo (new Response())->success('Registro Alterado com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    /**
     * ordem de compra
     */

    public function indexPurchaseOrder()
    {
        return view('finan.purchaseOrder');
    }

    public function getToPurchaseOrder()
    {
        /* Busca todos os endereçamentos */
        if ($ordes = PurchaseOrder::get()) {
            $user = Auth::user();
            foreach ($ordes as $ord) {

                $aprovar = $user->hasPermissionTo('aprovar-ordem-de-compra') ?
                    "<span class=\"badge badge-success ajax-link\" data-url=\"" . route('financial.aproveOrdem', ['id' => $ord->id]) . "\"><i class=\"fas fa-pen\"></i> Aprovar</span>"
                    : '';

                $reprovar = $user->hasPermissionTo('reprovar-ordem-de-compra') ?
                    "<span class=\"badge badge-danger ml-3 ajax-link\" data-url=\"" . route('financial.reproveOrdem', ['id' => $ord->id]) . "\"><i class=\"fas fa-pen\"></i> Reprovar</span>"
                    : '';

                $data['data'][] = [
                    $ord->budget_number,
                    $ord->objective,
                    $ord->value,
                    $ord->status == 'Aprovado' ? "<span class=\"badge badge-success \" ><i></i>$ord->status</span>" : "<span class=\"badge badge-danger \" ><i></i>$ord->status</span>",
                    $ord->status == 'Em Análise' ?
                        $aprovar .
                        $reprovar     : ''
                    // "<a href=\"".url($bud->pdf)."\" data-fancybox=\"image\" class=\"badge badge-primary ml-3 fancybox\"><i class=\"far fa-file-image\"></i> CAPA</a>"
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function aproveOrdem(Request $request, $id)
    {
        $budget = PurchaseOrder::findorFail($id);
        $budget->status = 'Aprovado';
        $budget->save();

        echo (new Response())->success('Registro Alterado com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    public function reproveOrdem(Request $request, $id)
    {
        $budget = PurchaseOrder::findorFail($id);
        $budget->status = 'Reprovado';
        $budget->save();

        echo (new Response())->success('Registro Alterado com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    /**
     *caixinha
     */

    public function indexBox()
    {
        return view('finan.box');
    }

    public function indexMovement($id)
    {
        $history = HistoryMovingBox::where('id_moving_box', $id)->with('movingBox')->get();

        return view('finan.movement', compact('history'));
    }

    /**
     * CADASTRA OU ATUALIZA CAIXINHA
     * @param AdmBoxRequest $request
     * @return void
     */

    public function saveToBox(AdmBoxRequest $request)
    {
        $listPost            = $request->validated();
        $bank                = Bank::where('id', $request->id_bank)->first();
        $listPost['balance'] = $listPost['value'];
        $listPost['status']  = 'Aberto';
        $movi                = new MovingBox($listPost);
        $movi->save();

        $history                 = new HistoryMovingBox();
        $history->goal           = $listPost['goal'];
        $history->type           = 'Saque';
        $history->favored        = $listPost['responsible'];
        $history->value          = $listPost['value'];
        $history->status         = 'Aberto';
        $history->id_moving_box  = $movi->id;
        $history->save();

        $balance = $bank->balance - $listPost['value'];
        $bank->balance = $balance;
        $bank->update();

        if (!$movi) {
            echo (new Response())->error('Erro')->action('reloadDataTable', 'table')->json();
        }

        echo (new Response())->success('Registro Criado com Sucesso')->action('reloadDataTable', 'table')->json();
        //  return response()->success('Hello World')->action('reloadDataTable', 'table')->json();
        //  new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    /**
     * Busca Banco
     * @param Request $request
     * @return void
     */
    public function findSector(Request $request)
    {
        $title = filter_var($request->name, FILTER_SANITIZE_STRIPPED);

        if ($sectors = Sectors::where('name', 'LIKE', "%{$request->name}%")->get()) {
            foreach ($sectors as $sector) {
                $return[] = [
                    'id'   => $sector->id,
                    'text' => $sector->name
                ];
            }
        }
        echo json_encode($return ?? null);
    }

    public function getToBox()
    {
        /* Busca todos os endereçamentos */
        if ($movings = MovingBox::with(['sector', 'bank'])->get()) {

            $user = Auth::user();

            foreach ($movings as $mov) {

                $fechar = $user->hasPermissionTo('baixar-caixinha') ?
                    "<span class=\"badge badge-danger ajax-link\" data-url=\"" . route('box.closeBox', ['id' => $mov->id]) . "\"><i class=\"fas fa-pen\"></i> Fechar</span>"
                    : '';

                $repor = $user->hasPermissionTo('editar-caixinha') ?
                    "<a class='badge badge-success ml-3'  onclick='OpenModalFor($mov->id)' role='button'><i class='fas fa-pen'></i> Repor</a>"
                    : '';

                $retirar = $user->hasPermissionTo('editar-caixinha') ?
                    "<a class='badge badge-success ml-3'  onclick='OpenModalForRemove($mov->id)' role='button'><i class='fas fa-pen'></i> Retirar</a>"
                    : '';




                $data['data'][] = [
                    $mov->id,
                    'R$ ' . $mov->balance,
                    $mov->sector->name,
                    $mov->bank->name,
                    $mov->responsible,
                    $mov->status,
                    $mov->status == 'Fechado' ?
                        " " :
                    $fechar .
                    $repor  .
                    $retirar   ,
                    $user->hasPermissionTo('ver-movimentacoes-caixinha') ?
                        "<a href='caixinha/movimentacao/$mov->id' class='badge badge-success' role='button'>Ver Movimentações</a>"
                        : ''
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function closeBox(Request $request, $id)
    {
        $box                           = MovingBox::findorFail($id);
        $history                       = new HistoryMovingBox();
        $history->goal                 = 'Ref. Fechamento do Caixinha id ' .  $id . ' do ' . $box->responsible;
        $history->type                 = 'Fechamento';
        $history->favored              = $box->responsible;
        $history->value                = $box->balance;
        $history->status               = 'Fechado';
        $history->id_moving_box        = $box->id;
        $history->save();

        $box->status = 'Fechado';
        $box->update();

        return (new Response())->success('Registro Alterado com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    public function resetBox(Request $request)
    {
        $box                     = MovingBox::findorFail($request->id);
        $history                 = new HistoryMovingBox();
        $history->goal           = 'Ref. Reabastecimento da Caixinha id ' .  $request->id . ' do ' . $box->responsible . ' observações: ' . $request->observation;
        $history->type           = 'Reabastecimento';
        $history->favored        = $box->responsible;
        $history->value          = $request->value;
        $history->status         = 'Aberto';
        $history->id_moving_box  = $box->id;
        $history->save();

        $box->balance += $request->value;
        $box->update();

        echo (new Response())->success('Valor Solicitado com Sucesso')->action('loadForm', true)->action('reloadDataTable', 'table')->json();
    }

    public function removetBox(Request $request)
    {
        $box       = MovingBox::findorFail($request->id);
        $bank      = Bank::findorFail($box->id_bank);

        if ($bank->balance < $request->value) {

            return response()->json([
                'success' => false,
                'message' => 'Banco sem valor disponível, valor: ' . $bank->balance
            ], 403);
        }

        $history                 = new HistoryMovingBox();
        $history->goal           = 'Ref. Retirada da Caixinha id ' .  $request->id . ' do ' . $box->responsible . ' observações: ' . $request->observation;;
        $history->type           = 'Retirada';
        $history->favored        = $box->responsible;
        $history->value          = $request->value;
        $history->status         = 'Aberto';
        $history->id_moving_box  = $box->id;
        $history->save();

        $box->balance -= $request->value;
        $box->update();

        echo (new Response())->success('Valor Retirado com Sucesso')->action('loadForm', true)->action('reloadDataTable', 'table')->json();
    }


    public function getToBoxModal()
    {
        if ($movings = MovingBox::with(['sector', 'bank'])->get()) {

            foreach ($movings as $mov) {
                $data['data'][] = [
                    'R$ ' . $mov->balance,
                    $mov->sector->name,
                    $mov->bank->name,
                    $mov->responsible,
                    $mov->status,
                    "<span class=\"badge badge-danger ajax-link\" data-url=\"" . route('box.closeBox', ['id' => $mov->id]) . "\"><i class=\"fas fa-pen\"></i> Fechar</span>" .
                        "<span class='badge badge-success ml-3'   data-toggle='modal' data-target='#modal-default'><i class='fas fa-pen'></i> Repor</span>",
                    '<button class="badge badge-success" data-toggle="modal" data-target="#modal-default">NOVA CAIXINHA</button>'
                ];
            }
        }

        $data['movings'] = $movings;

        echo json_encode(($data ?? ['data' => []]));
    }

    public function indexMove()
    {
        return view('finan.movementBox');
    }

    public function getToMove()
    {
        /* Busca todos os endereçamentos */
        if ($movings = HistoryMovingBox::with(['movingBox'])->get()) {
            foreach ($movings as $mov) {
                $data['data'][] = [
                    $mov->id,
                    'R$ ' . $mov->value,
                    $mov->movingBox->bank->name,
                    $mov->favored,
                    $mov->goal,
                    $mov->type,
                    $mov->status,
                    "<a class='badge badge-success ml-3'  onclick='OpenModalFor($mov->id)' role='button'><i class='fas fa-pen'></i> Prestar Conta</a>",
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }
}
