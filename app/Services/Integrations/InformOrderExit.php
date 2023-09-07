<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\InformerOrderExit;
use App\Http\Adapters\Versa\VersaInformOrderExit;
use App\Models\Integrations;
use App\Models\OrderExits;

class InformOrderExit {

    protected static array $resolveHandler = [
        'Versa' => VersaInformOrderExit::class
    ];

    /**
     * INFORMA QUANDO O PEDIDO DE SAIDA FOR RECEBIDO
     * @param OrderExits $orderExit
     * @return InformerOrderExit|null
     */
    public static function orderExit(OrderExits $orderExit): ?InformerOrderExit {
        $integration = Integrations::where('partner_id',$orderExit->partner_id)->first();
        if(array_key_exists($orderExit->third_system,self::$resolveHandler)){
            return (new self::$resolveHandler[$orderExit->third_system]())->send($integration, $orderExit);
        }
        return null;
    }

}
