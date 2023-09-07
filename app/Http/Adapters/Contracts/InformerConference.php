<?php

namespace App\Http\Adapters\Contracts;

use App\Models\Integrations;
use App\Models\OrderExits;

interface InformerConference {

    /**
     * INFORMA QUANDO A CONFERENCIA FOR CONCLUIDA
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return array|null
     */
    public function send(Integrations $integration, OrderExits $orderExit);

}
