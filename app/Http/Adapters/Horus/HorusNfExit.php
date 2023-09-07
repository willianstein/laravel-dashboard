<?php

namespace App\Http\Adapters\Horus;

use App\Http\Adapters\Contracts\NfExitAdapter;
use App\Models\DataTransferObjects\ImportNfDto;
use App\Models\IntegrationItems;
use App\Models\OrderExits;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class HorusNfExit extends NfExitAdapter{

    /**
     * DA BOOT NO ADAPTADOR
     * @param IntegrationItems $integrationItem
     * @return static
     */
    public static function boot(IntegrationItems $integrationItem): NfExitAdapter {
        return new self($integrationItem);
    }

    /**
     * VERIFICA SE A NF JÁ ESTA CADASTRADA NO EMS
     * @param array $adapterNf
     * @return bool
     */
    public function hasNfOnBBEms(array $adapterNf): bool {
        // TODO: Implement hasNfOnBBEms() method.
        return false;
    }

    /**
     * VERIFICA SE EXISTEM NOTAS A SEREM IMPORTADAS
     * DEVEMOS RETORNAR UM ARRAY DE ARRAY'S CONTENDO:
     * ['third_system_id' => "iD DO PEDIDO DO TERCEIRO", "xmlString" = "CONTENDO O XML (STRING)"]
     *
     * @return Collection
     */
    public function getAdapterNfs(): Collection {

        $ordersExit = OrderExits::where('partner_id',$this->integrationItem->integration->partner_id)
                                ->where('created_at', '>', '2023-06-18')
                                ->where('status','Aguardando Transporte')
                                ->where('third_system','Horus')
                                ->whereNotNull('third_system_id')
                                ->whereNull('invoice')
                                ->get();

        if(!$ordersExit->isEmpty()){
            foreach ($ordersExit as $orderExit) {
                if($nfe = $this->searchOnHorus($orderExit)){
                    $nfs[] = [
                        'COD_PED_VENDA' => $nfe['COD_PED_VENDA'],
                        'XML_Base64' => $nfe['XML_Base64'],
                        'NRO_NOTA_FISCAL' => $nfe['NRO_NOTA_FISCAL']
                    ];
                }
            }
        }

        return collect($nfs??[]);
    }

    /**
     * CRIA OS DATA TRANSFER OBJECTS DAS NOTAS FISCAIS
     * PARA INSERIR NO EMS
     * @param array $adapterNf
     * @return ImportNfDto
     */
    public function processNfs(array $adapterNf): ImportNfDto {
        return new ImportNfDto(
            $adapterNf['COD_PED_VENDA'],
            base64_decode($adapterNf['XML_Base64']),
            $adapterNf['NRO_NOTA_FISCAL']
        );
    }

    private function searchOnHorus(OrderExits $orderExit): ?array {
        $params = json_decode($this->integrationItem->params,true);
        $params['COD_PED_VENDA'] = $orderExit->third_system_id;
        $params['XML_BASE64'] = "S";
        try {

            $nfe = Http::withBasicAuth($this->integrationItem->integration->user,$this->integrationItem->integration->password)
                ->get($this->integrationItem->integration->url.'/Busca_NotaFiscal',$params);

            if($nfe->failed()){
                return null;
            }

            $nfe = $nfe->collect()->first();

            if(!empty($nfe['Falha'])){
                $this->addLogLine(
                    "\nO Pedido {$orderExit->third_system_id} retornou erro. {$nfe['Falha']}: {$nfe['Mensagem']}"
                );
                return null;
            }

            return $nfe;

        } catch (\Exception $exception) {
            $this->addLogLine("Erro desconhecido: {$exception->getMessage()}");
            return null;
        }
    }

    /**
     * INFORMA SE NECESSARIO QUE A NOTA FOI RECEBIDA
     * @param ImportNfDto $nfDto
     * @return bool
     */
    public function confirmInvoiceImport(ImportNfDto $nfDto): bool {
        return true;
    }
}
