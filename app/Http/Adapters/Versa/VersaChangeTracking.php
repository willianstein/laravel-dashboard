<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\InformerChangeTracking;
use App\Models\History\History;
use App\Models\Integrations;
use App\Models\OrderExits;
use Illuminate\Support\Facades\Http;

class VersaChangeTracking implements InformerChangeTracking {


    /**
     * INFORMA QUANDO O STATUS DO PEDIDO MUDAR
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return array|null
     */
    public function send(Integrations $integration, OrderExits $orderExit) {

        if(empty($orderExit->transportTag)){
            return null;
        }

        $dataPost = [
            'CodConferencia'    => $orderExit->third_system_id,
            'Codigorastreio'    => $orderExit->transportTag->tag_code,
            'Situacaorastreio'  => $this->resolveStatus($orderExit)
        ];

        $status = Http::withToken($integration->token)->post($integration->url.'/wms/separacaorastreiosituacao',$dataPost);

        if($status->clientError()){
            (new History($orderExit))->description("Ao informar o rastreio {$orderExit->status}, ocorreu um erro local.")->save();
            return null;
        }

        if($status->serverError()){
            (new History($orderExit))->description("Ao informar o rastreio {$orderExit->status}, o versa retornou.")->save();
            return null;
        }

        (new History($orderExit))
            ->description("Envio do Cod. Rastreio ({$dataPost['Codigorastreio']}) da conferencia {$orderExit->third_system_id} informado com sucesso para o versa.")
            ->notes(json_encode($dataPost))
            ->save();
        return null;
    }

    private function resolveStatus(OrderExits $orderExit): ?int {
        /* Faz a Troca dos Status no Versa Conforme o Status do Pedido */
        $dataResolve = [
            'Aguardando Transporte' => 8,
            'Em Transito'           => 1,
            //'Concluído'             => 4 //So Podemos Habilitar Depois Que Integrar com as Transportadoras.
        ];
        if(array_key_exists($orderExit->status,$dataResolve)){
            return $dataResolve[$orderExit->status];
        }
        return null;
    }
}
