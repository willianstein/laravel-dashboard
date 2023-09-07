<?php

namespace App\Http\Adapters\Contracts;

use App\Models\Integrations;
use App\Models\Orders;

interface SingleNfe {

    /**
     * BUSCA A NFe NO ERP
     * @param Integrations $integration
     * @param Orders $order
     * @return mixed
     */
    public function get(Integrations $integration, Orders $order);

}
