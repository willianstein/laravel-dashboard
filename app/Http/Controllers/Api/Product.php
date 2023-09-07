<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Models\Products;
use App\Http\Requests\ApiProductRequest;
use App\Http\Resources\ApiProductResource;

/**
 * ENDPOINT GESTÃO DE PRODUTOS
 */
class Product extends Controller {

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request) {
        /* Checa se tem isbn */
        if(empty($request->isbn)){
            return self::error('ISBN ausente');
        }

        /* Busca Produto */
        $product = Products::where('isbn',$request->isbn)->first();
        if(empty($product->id)){
            return self::info('ISBN não encontrado');
        }

        /* Retorno Sucesso */
        return self::success(null,ApiProductResource::make($product));
    }

    /**
     * @param ApiProductRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(ApiProductRequest $request) {
        /* Salvar */
        if(!$product = Products::create($request->validated())){
            return self::error('Falha ao cadastrar produto');
        }

        /* Retorno Sucesso */
        return self::success(null,ApiProductResource::make($product));
    }
}
