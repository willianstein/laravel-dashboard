<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\InformerChangeStatus;
use App\Models\History\History;
use App\Models\Integrations;
use App\Models\Orders;
use Illuminate\Support\Facades\Http;

class VersaChangeStatus implements InformerChangeStatus {


    /**
     * INFORMA QUANDO O STATUS DO PEDIDO MUDAR
     * @param Integrations $integration
     * @param Orders $order
     * @return array|null
     */
    public function send(Integrations $integration, Orders $order) {

        $status = Http::withToken($integration->token)->post($integration->url.'/wms/separacaosituacao',[
            'CodConferencia'=> $order->third_system_id,
            'Indseparacao'  => $this->resolveStatus($order)
        ]);

        if($status->clientError()){
            (new History($order))->description("Ao informar a mudança de status {$order->status}, ocorreu um erro no versa.")->save();
            return null;
        }

        if($status->serverError()){
            (new History($order))->description("Ao informar a mudança de status {$order->status}, o versa retornou.")->save();
            return null;
        }

        (new History($order))->description("Troca de status ({$order->status}), informada com sucesso para o versa.")->save();
        return null;
    }

    private function resolveStatus(Orders $order): ?int {

        /* Mapeamento Status Saida */
        $dataResolve['saida'] = [
            'Aguardando Separação'      => 9,
            'Aguardando Conferência'    => 1
        ];

        /* Mapeamento Status Entrada */
        $dataResolve['entrada'] = [
            'Aguardando Conferência'    => 10
        ];

        if(array_key_exists($order->status,$dataResolve[$order->type])){
            return $dataResolve[$order->type][$order->status];
        }
        return null;
    }
}
