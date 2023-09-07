<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\InformerConference;
use App\Http\Adapters\Horus\HorusInformerConference;
use App\Http\Adapters\Versa\VersaInformerConference;
use App\Models\Integrations;
use App\Models\OrderExits;

class InformConclusionConference {

    protected static array $resolveHandler = [
        'Versa' => VersaInformerConference::class,
        'Horus' => HorusInformerConference::class
    ];

    /**
     * INFORMA QUANDO A CONFERENCIA FOR CONCLUIDA
     * @param OrderExits $orderExit
     * @return InformerConference|null
     */
    public static function orderExit(OrderExits $orderExit): ?InformerConference {
        $integration = Integrations::where('partner_id',$orderExit->partner_id)->first();
        if(array_key_exists($orderExit->third_system,self::$resolveHandler)){
            return (new self::$resolveHandler[$orderExit->third_system]())->send($integration, $orderExit);
        }
        return null;
    }

}
