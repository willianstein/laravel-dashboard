<?php

namespace App\Http\Adapters\Contracts;

use App\Models\Integrations;
use App\Models\OrderExits;

interface InformerOrderExit {

    /**
     * INFORMA QUANDO O PEDIDO DE SAIDA FOR RECEBIDO
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return InformerOrderExit|null
     */
    public function send(Integrations $integration, OrderExits $orderExit): ?InformerOrderExit;

}
