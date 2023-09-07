<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\InformerOrderExit;
use App\Models\History\History;
use App\Models\Integrations;
use App\Models\OrderExits;
use Illuminate\Support\Facades\Http;

class VersaInformOrderExit implements InformerOrderExit {

    /**
     * INFORMA QUANDO O PEDIDO DE SAIDA FOR RECEBIDO
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return InformerOrderExit|null
     */
    public function send(Integrations $integration, OrderExits $orderExit): ?InformerOrderExit {

        $response = Http::withToken($integration->token)
            ->get($integration->url."/wms/listaseparacao/{$orderExit->third_system_id}/marca");

        if($response->failed()){
            /* Histórico */
            (new History($orderExit))->description("Falhou ao informar pedido recebido no Versa.")->save();
            return null;
        }

        /* Histórico */
        (new History($orderExit))->description("Informado ao versa o recebimento da conferencia {$orderExit->third_system_id}")->save();

        return $this;

    }
}
