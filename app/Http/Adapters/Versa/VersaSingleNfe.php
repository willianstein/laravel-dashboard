<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\SingleNfe;
use App\Models\Integrations;
use App\Models\Orders;

class VersaSingleNfe implements SingleNfe {

    /**
     * BUSCA A NFe NO ERP
     * @param Integrations $integration
     * @param Orders $order
     * @return mixed
     */
    public function get(Integrations $integration, Orders $order) {

        dd($integration->toArray(),$order->toArray());

    }
}
