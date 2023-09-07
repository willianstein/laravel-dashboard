<?php

namespace App\Http\Controllers\Adm;

use App\Http\Adapters\Horus\HorusTag;
use App\Http\Libraries\Response;
use App\Models\TagTransportProviders;
use App\Services\Integrations\InformTracking;
use http\Exception;
use Illuminate\Http\Request;

use App\Http\Adapters\Vipp\VipTag;
use App\Http\Controllers\Controller;
use App\Models\OrderExits;
use App\Models\OrderTransportTags;

class TransportTag extends Controller {

    public function generate(OrderExits $orderExit) {

        if(!empty($third_system_name = $orderExit->hasIntegration->third_system_name)){

            if($third_system_name == "Versa" && strtolower(trim($orderExit->transport->carrier_name??"")) == "correios"  && empty($orderExit->transportTag)){

                /* Gera a Etiqueta */
                $vipTag = VipTag::run($orderExit)->generate();
                $dataVolumes = $vipTag['Volumes'][0];

                /* Verifica Erro */
                if($vipTag['StatusPostagem'] == "Valida"){

                    /* Salva no Banco */
                    $oderTransportTag = OrderTransportTags::create([
                        'order_id'      => $orderExit->id,
                        'bill_lading'   => $dataVolumes['IdConhecimento'],
                        'tag_code'      => $dataVolumes['Etiqueta'],
                        'price'         => $dataVolumes['ValorTarifa'],
                        'delivery_time' => $dataVolumes['DiasUteisPrazo'],
                        'status'        => 'Valida',
                        'metadata'      => $vipTag->toJson()
                    ]);

                    /* Informa Rastreio (Se houver integração) */
                    $orderExit->refresh();
                    InformTracking::orderExit($orderExit);

                    (new Response())->success('Etiqueta criada com sucesso')->flash();
                    return redirect(route('adm.expeditionExit.manager',$orderExit));

                }

                (new Response())->error('Ops Falha ao Criar a Etiqueta')->flash();

            }

        }

    }

    public function print(OrderExits $orderExit) {

        if(empty($third_system_name = $orderExit->hasIntegration->third_system_name)){
           abort(400,"Parceiro não integrado");
        }

        /* Versa */
        if($third_system_name == "Versa"){
            return VipTag::run($orderExit)->print();
        }

        /* Horus */
        if($third_system_name == "Horus"){
            return HorusTag::run($orderExit)->print();
        }

    }

}
