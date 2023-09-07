<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\SingleNfe;
use App\Http\Adapters\Versa\VersaSingleNfe;
use App\Models\Integrations;
use App\Models\Orders;

class GetNfe {

    protected static array $resolveHandler = [
        'Versa' => VersaSingleNfe::class
    ];

    /**
     * BUSCA UMA NFe MANUALMENTE
     * @param Orders $order
     * @return SingleNfe|null
     */
    public static function order(Orders $order): ?SingleNfe {
        $integration = Integrations::where('partner_id',$order->partner_id)->first();
        if(array_key_exists($order->third_system,self::$resolveHandler)){
            return (new self::$resolveHandler[$order->third_system]())->get($integration, $order);
        }
        return null;
    }

}
