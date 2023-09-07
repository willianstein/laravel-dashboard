<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\InformerChangeTracking;
use App\Http\Adapters\Versa\VersaChangeTracking;
use App\Models\Integrations;
use App\Models\OrderExits;

class InformTracking {

    protected static array $resolveHandler = [
        'Versa' => VersaChangeTracking::class
    ];

    /**
     * INFORMA QUANDO A CONFERENCIA FOR CONCLUIDA
     * @param OrderExits $orderExit
     * @return InformerChangeTracking|null
     */
    public static function orderExit(OrderExits $orderExit): ?InformerChangeTracking {
        $integration = Integrations::where('partner_id',$orderExit->partner_id)->first();
        if(array_key_exists($orderExit->third_system,self::$resolveHandler)){
            return (new self::$resolveHandler[$orderExit->third_system]())->send($integration, $orderExit);
        }
        return null;
    }

}
