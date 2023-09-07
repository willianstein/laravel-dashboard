<?php

namespace App\Http\Adapters\Contracts;

use App\Models\Integrations;
use App\Models\OrderExits;

interface InformerChangeStatus {

    /**
     * INFORMA QUANDO O STATUS DO PEDIDO MUDAR
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return array|null
     */
    public function send(Integrations $integration, OrderExits $orderExit);

}
