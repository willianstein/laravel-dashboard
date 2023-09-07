<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmStockRequest;
use Illuminate\Http\Request;
use App\Models\Stocks;
use App\Models\Offices;
use App\Models\Partners;
use App\Models\Products;
use App\Models\Addressings;
use Illuminate\Support\Facades\Auth;

/**
 *  CADASTRO DE ESTOQUE
 */
class Stock extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:estoque|cadastrar-estoque|ver-estoque|importar-estoque-csv|editar-estoque',
            ['only' => ['index', 'save', 'getPartners', 'getProducts', 'getAddressing', 'getStocks', 'getStock']]
        );
    }
    /**
     * PAGINA INICIAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $offices = Offices::all();
        $dropStockTypes = Stocks::TYPES;
        return view('adm.stock', compact('offices', 'dropStockTypes'));
    }

    /**
     * CRIA OU ATUALIZA UM ESTOQUE
     * @param AdmStockRequest $request
     * @return void
     */
    public function save(AdmStockRequest $request)
    {
        $listPost = $request->validated();
        if (!Stocks::updateOrCreate(['id' => ($listPost['id'] ?? 0)], $listPost)->save()) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }
        echo (new Response())->success('Registro Salvo com Sucesso')->action('reloadDataTable', 'table')->json();
    }

    /**
     * RETORNS OS PARCEIROS (DROPDOWN)
     * @param Request $request
     * @return void
     */
    public function getPartners(Request $request)
    {

        $document01 = preg_replace('/[^0-9]/', '', $request->terms);
        $name = filter_var($request->term, FILTER_SANITIZE_STRIPPED);

        if ($partners = Partners::where('document01', 'LIKE', "%{$request->term}%")->orWhere('name', 'LIKE', "%{$name}%")->get()) {
            foreach ($partners as $partner) {
                $return[] = [
                    'id' => $partner->id,
                    'text' => "(" . str_convert_to_document($partner->document01) . ") {$partner->name}"
                ];
            }
        }
        echo json_encode($return ?? null);
    }

    /**
     * RETORNA OS PROSUTOS (DROPDOWN)
     * @param Request $request
     * @return void
     */
    public function getProducts(Request $request)
    {

        $title = filter_var($request->term, FILTER_SANITIZE_STRIPPED);

        if ($products = Products::where('isbn', 'LIKE', "%{$request->term}%")->orWhere('title', 'LIKE', "%{$title}%")->get()) {
            foreach ($products as $product) {
                $return[] = [
                    'id' => $product->id,
                    'text' => "({$product->isbn}) {$product->title}"
                ];
            }
        }
        echo json_encode($return ?? null);
    }

    /**
     * RETORNA OS ENDEREÇAMENTOS (DROPDOWN)
     * @param Request $request
     * @return void
     */
    public function getAddressing(Request $request)
    {
        $office_id = filter_var($request->term, FILTER_SANITIZE_NUMBER_INT);
        if ($addressings = Addressings::where('office_id', $office_id)->get()) {
            foreach ($addressings as $addressing) {
                $data[$addressing->id] = $addressing->name;
            }
        }
        echo json_encode($data ?? null);
    }

    /**
     * RETORNA OS REGISTROS DO ESTOQUE (DATATABLE)
     * @return void
     */
    public function getStocks()
    {
        $user = Auth::user();
        if ($stocks = Stocks::with('office')->with('partner')->with('product')->with('addressing')->get()->sortBy('partner.name')) {
            foreach ($stocks as $stock) {
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
                    $user->hasPermissionTo('editar-estoque') ?
                        "<span class=\"badge badge-success ajax-link\" data-obj=\"myForm\" data-url=\"" . route('adm.stock.getStock', ['stock' => $stock->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                        : '',
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    /**
     * RETORNA UM REGISTRO DO ESTOQUE POR ID
     * @param Stocks $stock
     * @return void
     */
    public function getStock(Stocks $stock)
    {
        echo (new Response())->action('loadForm', 'myForm')->data($stock->toArray())->json();
    }

    public function getDropByPartnerAndProduct(Partners $partner, Products $product, string $type)
    {
        if ($stocks = Stocks::with('addressing')
            ->where('partner_id', $partner->id)
            ->where('product_id', $product->id)
            ->where('type', $type)
            ->get()
        ) {
            foreach ($stocks as $stock) {
                $data[$stock->id] = "{$stock->addressing->name} (Min: {$stock->quantity_min} Max: {$stock->quantity_max} Atual: {$stock->quantity})";
            }
            echo json_encode($data ?? null);
        }
    }
}
