<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\InformerConference;
use App\Models\History\History;
use App\Models\Integrations;
use App\Models\OrderExits;
use Illuminate\Support\Facades\Http;

class VersaInformerConference implements InformerConference {

    /**
     * INFORMA QUANDO A CONFERENCIA FOR CONCLUIDA
     * @param Integrations $integration
     * @param OrderExits $orderExit
     * @return InformerConference| null
     */
    public function send(Integrations $integration, OrderExits $orderExit) {

        $volumes = $this->getVolumes($orderExit);

        $conference = [
            "CodConferencia"    => $orderExit->third_system_id,
            "Indseparacao"      => 0,
            "DatInicio"         => $orderExit->created_at->format('Y-m-d H:m:s'),
            "DatConclusao"      => date("Y-m-d H:m:s"),
            "Qtdvolumes"        => ($volumes->numberPackages??0),
            "EspecieVolume"     => ($volumes->species??"VOL"),
            "PesoTotal"         => (float) ($volumes->weightPackages / 1000),
            "Observ"            => ""
        ];

        foreach ($orderExit->items as $item){

            $versaProduct = $product = Http::withToken($integration->token)
                ->get($integration->url.'/produto/isbn/'.$item->isbn);

            if($versaProduct->collect()->isEmpty()){
                /* Histórico */
                (new History($orderExit))->description("Ao informar conferencia para o versa, falhou na busca do ISBN: {$item->isbn}")->save();
                return null;
            }

            $versaProduct = $versaProduct->collect()->first();

            /* Vindo do EMS */
            $weightProduct       = $item->product->weight;
            $weightTotalProducts = (float) (($weightProduct * $item->quantity) / 1000);
            /* Vindo do Versa */
            $weightProduct       = $versaProduct['peso'];
            $weightTotalProducts = (float) $weightProduct * $item->quantity;

            $conference['PesoTotal'] += $weightTotalProducts;
            $conference['Itens'][] = [
                "codProduto" => ($versaProduct['codproduto']),
                "nomProduto" => $item->product->title,
                "isbn" => $item->isbn,
                "peso" => $weightProduct,
                "localizacao" => "",
                "dscgrupo" => "",
                "quantidade" => $item->quantity,
                "pesoTotal" => $weightTotalProducts,
                "nrocaixa" => 1
            ];
        }

        if(Http::withToken($integration->token)->post($integration->url.'/wms/separacaoretorno',$conference)->failed()){
            (new History($orderExit))->description("Ao informar conferencia para o versa, o versa retornou erro.")->save();
            return null;
        }

        (new History($orderExit))->description("Conferencia do pedido (Id:{$orderExit->id} - Cod: {$orderExit->third_system_id}) enviada com sucesso para o versa.")->save();
        return $this;
    }

    private function getVolumes(OrderExits $orderExit): object {

        $return = [
            'numberPackages'    => 0,
            'weightPackages'    => 0,
            'speciesPackages'   => 0
        ];

        foreach ($orderExit->packages as $package){
            $return['numberPackages'] += $package->quantity;
            $return['weightPackages'] += $package->package->weight * $package->quantity;
            $return['speciesPackages'] = $package->package->species;
        }

        return (object) $return;

    }


    private function quantitiesOfProducts(OrderExits $orderExit): object {

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
