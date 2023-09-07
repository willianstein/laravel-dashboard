<?php

namespace App\Http\Adapters\Horus;

use App\Http\Adapters\Contracts\InformerConference;
use App\Models\History\History;
use App\Models\IntegrationItems;
use App\Models\Integrations;
use App\Models\OrderExits;
use App\Models\OrderItemExits;
use App\Models\OrderPackages;
use Illuminate\Support\Facades\Http;

class HorusInformerConference implements InformerConference {

    /**
     * INFORMA QUANDO A CONFERENCIA FOR CONCLUIDA
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return array|null
     */
    public function send(Integrations $integration, OrderExits $orderExit) {

        try {
            $metaData = $this->getMetaData($integration);

            /* Busca Pedido */
            $orderHorus = Http::withBasicAuth($integration->user,$integration->password)
                ->get($integration->url.'/Busca_PedidosVenda',[
                    'COD_EMPRESA'   => $metaData->COD_EMPRESA,
                    'COD_FILIAL'    => $metaData->COD_FILIAL,
                    'COD_PED_VENDA' => $orderExit->third_system_id
                ]);

            /* Verifica */
            if($orderHorus->failed()){
                (new History($orderExit))->description("Ao informar conferencia: Não foi possivel recuperar o pedido: {$orderExit->third_system_id}")->save();
                return null;
            }

            /* Pula Expedição */
            $jumpExpeditionHorus = Http::withBasicAuth($integration->user,$integration->password)
                ->get($integration->url.'/Pular_expedicao',[
                    'COD_EMPRESA'   => $metaData->COD_EMPRESA,
                    'COD_FILIAL'    => $metaData->COD_FILIAL,
                    'COD_LOCAL'     => $metaData->COD_LOCAL,
                    'COD_PED_VENDA' => $orderExit->third_system_id,
                    'COD_CLI'       => ($orderHorus->collect()->first())['COD_CLI']
                ]);
            $jumpExpeditionHorus = $jumpExpeditionHorus->collect()->first();

            /* Verifica Sucesso */
            if($jumpExpeditionHorus['MSG'] != "PEDIDO ATUALIZADO COM SUCESSO!"){
                /* Histórico */
                (new History($orderExit))->description("Ao informar conferencia o Horus retornou: {$jumpExpeditionHorus['MSG']}")->save();
                return null;
            }

            /* Quantidade e Volumes */
            $quantities = $this->quantities($orderExit);
            $volumeHorus = Http::withBasicAuth($integration->user,$integration->password)
                ->get($integration->url.'/InsVolume_Pedido',[
                    'COD_EMPRESA'   => $metaData->COD_EMPRESA,
                    'COD_FILIAL'    => $metaData->COD_FILIAL,
                    'COD_PED_VENDA' => $orderExit->third_system_id,
                    'COD_CLI'       => ($orderHorus->collect()->first())['COD_CLI'],
                    'PES_VOLUME'    => ((int)($quantities->weightProducts / $quantities->quantityItems)) / 1000
                ]);

            /* Verifica */
            if($volumeHorus->failed()){
                /* Histórico */
                (new History($orderExit))->description("Ao informar conferencia: não foi possivel informar o volume")->save();
                return null;
            }

        } catch (\Exception $exception) {
            /* Histórico */
            (new History($orderExit))->description(substr("Erro desconhecido: {$exception->getMessage()}", 0, 250))->save();
            return null;
        }

    }


    /**
     * Retorna todos os parametros informados no campo params de uma determinada integração.
     * @param Integrations $integration
     * @return object
     */
    private function getMetaData(Integrations $integration): object {

        $metaData = [];

        if($itensIntegration = IntegrationItems::where('integration_id',$integration->id)->get()){
            foreach ($itensIntegration as $item){
                if (!empty($item->params)){
                    $metaData = array_merge($metaData,json_decode($item->params, true));
                }
            }
        }

        return (object) $metaData;
    }

    private function quantities(OrderExits $orderExit): object {

        $return = [
            'weightProducts' => 0,
            'quantityItems' => 0
        ];

        foreach ($orderExit->items as $item){
            $return['weightProducts'] += ((int) $item->product->weight) * $item->quantity;
            $return['quantityItems'] += $item->quantity;
        }

        return (object) $return;

    }
}
