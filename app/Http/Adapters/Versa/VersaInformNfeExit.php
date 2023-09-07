<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\InformerOrderExit;
use App\Models\History\History;
use App\Models\Integrations;
use App\Models\OrderExits;
use Illuminate\Support\Facades\Http;

class VersaInformNfeExit implements InformerOrderExit {

    /**
     * INFORMA QUANDO A NFE FOR RECEBIDA
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return InformerOrderExit|null
     */
    public function send(Integrations $integration, OrderExits $orderExit): ?InformerOrderExit {

        $response = Http::withToken($integration->token)
            ->get($integration->url."/wms/listanotas/{$orderExit->invoice->invoice_code}/marca");

        if($response->failed()){
            /* Histórico */
            (new History($orderExit))->description("Falhou ao informar NFe recebida no Versa.")->save();
            return null;
        }

        /* Histórico */
        (new History($orderExit))->description("Informado ao versa o recebimento da NFe {$orderExit->invoice->invoice_code}")->save();

        return $this;

    }
}
