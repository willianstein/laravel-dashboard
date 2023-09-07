<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\InformerChangeStatus;
use App\Http\Adapters\Versa\VersaChangeStatus;
use App\Models\Integrations;
use App\Models\Orders;

class InformStatus {

    protected static array $resolveHandler = [
        'Versa' => VersaChangeStatus::class
    ];

    /**
     * INFORMA QUANDO A CONFERENCIA FOR CONCLUIDA
     * @param Orders $order
     * @return InformerChangeStatus|null
     */
    public static function order(Orders $order): ?InformerChangeStatus {
        $integration = Integrations::where('partner_id',$order->partner_id)->first();
        if(array_key_exists($order->third_system,self::$resolveHandler)){
            return (new self::$resolveHandler[$order->third_system]())->send($integration, $order);
        }
        return null;
    }

}
