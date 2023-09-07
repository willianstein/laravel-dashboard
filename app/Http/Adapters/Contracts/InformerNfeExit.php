<?php

namespace App\Http\Adapters\Contracts;

use App\Models\Integrations;
use App\Models\OrderExits;

interface InformerNfeExit {

    /**
     * INFORMA QUANDO A NFE FOR RECEBIDA
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return InformerOrderExit|null
     */
    public function send(Integrations $integration, OrderExits $orderExit): ?InformerNfeExit;

}
