<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\NfExitAdapter;
use App\Models\IntegrationItems;
use App\Models\OrderExits;
use \Exception;
use Illuminate\Support\Facades\Storage;

class NfExitIntegration {

    public static function run() {

        if(!$integrations = IntegrationItems::where('type','nf_exits')->get()) {
            return;
        }

        foreach ($integrations as $integration){

            self::getNf($integration);

        }

    }

    public static function getNf(IntegrationItems $integrationItem) {

        /** @var NfExitAdapter $adapter */
        $adapter = ($integrationItem->adapter)::boot($integrationItem);
        $adapter->addLogLine("\nData: ".date('d-m-Y H:i:s'));
        $adapter->addLogLine("Tipo: NFe de Saida");
        $adapter->startLog();

        /* Verifica se o Servidor Esta Disponivel */
        if(!$adapter->checkServer()){return false; }

        /* Verifica se há Notas a Serem Importadas */
        $adapterNfs = $adapter->getAdapterNfs();
        if($adapterNfs->isEmpty()){
            $adapter->addLogLine('Não há notas de saida para importar');
        }

        foreach ($adapterNfs as $adapterNf){

            try {

                $importNfDto = $adapter->processNfs($adapterNf);
                if(!$orderExit = OrderExits::where('third_system_id',$importNfDto->getThirdSystemId())
                    ->where('partner_id',$integrationItem->integration->partner_id)->first()){
                    throw new \Exception("Não foi encontrado um pedido de saida com o codigo ({$importNfDto->getThirdSystemId()}) no EMS");
                }

                /* Log */
                $adapter->addLogLine("Processando a NF do pedido: {$orderExit->id}");
                $xmlName = "NFe/NF-Pedido-{$orderExit->id}";

                if(!empty($orderExit->invoice)){
                    Storage::disk('local')->delete($orderExit->invoice);
                }

                if(!Storage::disk('local')->put($xmlName, $importNfDto->getXmlString())){
                    throw new \Exception('Não foi possivel salvar o XML');
                }

                $orderExit->invoice = $xmlName;
                $orderExit->invoice_number = $importNfDto->getNumberNf();
                if(!$orderExit->save()){
                    throw new \Exception('Não foi possivel atualizar o pedido');
                }

                if(!$adapter->confirmInvoiceImport($importNfDto)){
                    throw new \Exception('Não foi possivel informar o recebimento da NF-e');
                }

            } catch (Exception $exception){
                $adapter->addLogLine("Erro: {$exception->getMessage()}");
                continue;
            }

        }

        $adapter->endLog();
        return true;

    }

}
