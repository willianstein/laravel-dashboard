<?php

namespace App\Http\Controllers\Adm;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmOrderItemRequest;
use App\Models\OrderItems;
use App\Models\Products;

/**
 *  CLASSE DOS ITENS DO PEDIDO
 */
class OrderItem extends Controller {

    /**
     * RETORNA TODOS OS ITEMS DO PEDIDO
     * @return void
     */
//    public function getItems() {
//        /* Busca todos os Offices */
//        if($items = OrderItems::all()){
//            foreach ($items as $item){
//                $data['data'][] = [
//                    "({$item->product->isbn}) {$item->product->title}",
//                    $item->quantity,
//                    $item->status,
//                    "<span class=\"badge badge-danger ajax-link\" data-url=\"".route('adm.orderEntry.removeItem',['orderEntry'=>$item->order_id,'orderItem'=>$item->id])."\"><small><i class=\"fas fa-ban\"></i></small> EXCLUIR</span>"
//                ];
//            }
//        }
//        echo json_encode(($data??['data'=>[]]));
//    }

    /**
     * BUSCA UM ITEM DO PEDIDO POR ISBN OU TITULO DO LIVRO
     * @param Request $request
     * @return void
     */
    public function findProduct(Request $request) {
        $title = filter_var($request->term, FILTER_SANITIZE_STRIPPED);

        if($products = Products::where('isbn','LIKE',"%{$request->term}%")->orWhere('title','LIKE',"%{$title}%")->get()){
            foreach ($products as $product){
                $return[] = [
                    'id' => $product->id,
                    'text' => "({$product->isbn}) {$product->title}"
                ];
            }
        }
        echo json_encode($return??null);
    }

}
