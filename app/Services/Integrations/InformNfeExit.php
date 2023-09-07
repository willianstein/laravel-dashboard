<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\InformerNfeExit;
use App\Http\Adapters\Versa\VersaInformNfeExit;
use App\Models\Integrations;
use App\Models\OrderExits;

class InformNfeExit {

    protected static array $resolveHandler = [
        'Versa' => VersaInformNfeExit::class
    ];

    /**
     * INFORMA QUANDO A NFe FOR RECEBIDA
     * @param OrderExits $orderExit
     * @return InformerNfeExit|null
     */
    public static function orderExit(OrderExits $orderExit): ?InformerNfeExit {
        $integration = Integrations::where('partner_id',$orderExit->partner_id)->first();
        if(array_key_exists($orderExit->third_system,self::$resolveHandler)){
            return (new self::$resolveHandler[$orderExit->third_system]())->send($integration, $orderExit);
        }
        return null;
    }

}
