<?php

namespace App\Http\Adapters\Contracts;

use App\Models\Integrations;
use App\Models\OrderExits;

interface InformerChangeTracking {

    /**
     * INFORMA RASTREIO DO PEDIDO
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return array|null
     */
    public function send(Integrations $integration, OrderExits $orderExit);

}
