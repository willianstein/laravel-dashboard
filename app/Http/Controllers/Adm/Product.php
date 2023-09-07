<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmProductRequest;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function Termwind\render;

class Product extends Controller
{
    function __construct()
    {
        $this->middleware('permission:produtos|cadastro-produtos|ver-produtos|ver-capa-produtos|editar-produtos|importar-produtos-csv|inativar-produtos',
        ['only' => ['index','save','getProducts', 'getProduct', 'save']]
     );
    }

    public function index()
    {
        return view('adm.product');
    }

    public function getProducts()
    {
        /* Busca todos os Products */

        $user = Auth::user();
        if ($products = Products::all()) {
            foreach ($products as $product) {
                /* Botão Ativo */
                $switchActive   = "<div class=\"custom-control custom-switch custom-switch-off-danger custom-switch-on-success\">\n"
                    . "    <input type=\"checkbox\" class=\"custom-control-input ajax-check\" data-url=\"" . route('adm.product.onOff', ['product' => $product->id]) . "\"  id=\"active_contact_{$product->id}\" " . (empty($product->active) ? "" : "checked") . ">\n"
                    . "    <label class=\"custom-control-label\" for=\"active_contact_{$product->id}\">Ativo?</label>\n"
                    . "</div>";

                $editar = $user->hasPermissionTo('editar-produtos') ?
                    "<span class=\"badge badge-success ajax-link\" data-url=\"" . route('adm.product.getProduct', ['product' => $product->id]) . "\"><i class=\"fas fa-pen\"></i> EDITAR</span>"
                    : "";
                $capa = $user->hasPermissionTo('ver-capa-produtos') ?
                   "<a href=\"{$product->cover_url}\" data-fancybox=\"image\" class=\"badge badge-primary ml-3 fancybox\"><i class=\"far fa-file-image\"></i> CAPA</a>"
                   : "";

                $data['data'][] = [
                    $product->isbn,
                    $product->title,
                    $product->publisher,
                    $product->category,
                    $user->hasPermissionTo('inativar-unidades') ? $switchActive : '',
                    $editar . $capa ,
                ];
            }
        }
        echo json_encode(($data ?? ['data' => []]));
    }

    public function getProduct(Products $product)
    {
        echo (new Response())->action('loadForm', 'myForm')->data($product->toArray())->json();
    }

    public function save(AdmProductRequest $request)
    {

        $listPost = $request->validated();
        $cover = $listPost['cover'] ?? null;
        unset($listPost['cover']);

        /* Create */
        if (empty($listPost['id'])) {
            if (!$product = Products::create($listPost)) {
                echo (new Response())->error('Erro ao salvar as informações')->json();
                return;
            }
        }

        /* Update */
        if (!empty($listPost['id'])) {
            $product = Products::find($listPost['id']);
            $product->fill($listPost);
            $product->save();
        }

        /* Cover */
        if (!empty($cover)) {
            if (!empty($product->cover)) {
                Storage::disk('public')->delete($product->cover);
            }
            $fileName = $listPost['isbn'] . "." . $cover->extension();
            $product->cover = $cover->storeAs('products', $fileName, 'public');
            $product->save();
        }

        echo (new Response())->success('Registro Salvo com Sucesso')
            ->action('clearForm', true)->action('reloadDataTable', 'table')->json();
    }
}
