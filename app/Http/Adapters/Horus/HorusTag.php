<?php

namespace App\Http\Adapters\Horus;

use App\Models\OrderExits;
use App\Models\TagTransportProviders;
use Illuminate\Support\Facades\Http;

class HorusTag {

    protected static $orderExit;

    public static function run(OrderExits $orderExit) {
        return new self($orderExit);
    }

    public function __construct(OrderExits $orderExit) {
        self::$orderExit = $orderExit;
    }

    public function print() {

        /** @var TagTransportProviders $credentials */
        $credentials = TagTransportProviders::where('partner_id',self::$orderExit->partner_id)->first();

        $tracking = Http::withBasicAuth($credentials->user,$credentials->password)
            ->get($credentials->url.'/Busca_RastreioPedido',[
                'COD_EMPRESA'   => $credentials->metaData()->COD_EMPRESA,
                'COD_FILIAL'    => $credentials->metaData()->COD_FILIAL,
                'COD_PED_VENDA' => self::$orderExit->third_system_id
            ]);

        if($tracking->failed() || empty($tag['CODIGO_BARRAS'])){
            throw new \Exception('Etiqueta não gerada no Horus',204);
        }

        $tag = $tracking->collect()->first();
        return redirect($credentials->metaData()->urlEtiqueta.'/'.$tag['CODIGO_BARRAS'].'.pdf');
    }

}
