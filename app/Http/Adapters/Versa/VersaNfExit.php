<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\NfExitAdapter;
use App\Models\DataTransferObjects\ImportNfDto;
use App\Models\IntegrationItems;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class VersaNfExit extends NfExitAdapter {

    /**
     * DA BOOT NO ADAPTADOR
     * @param IntegrationItems $integrationItem
     * @return NfExitAdapter
     */
    public static function boot(IntegrationItems $integrationItem): NfExitAdapter {
        return new self($integrationItem);
    }

    /**
     * CONSTRUTOR
     * @param IntegrationItems $integrationItem
     */
    public function __construct(IntegrationItems $integrationItem) {
        parent::__construct($integrationItem);
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
     * @return Collection
     */
    public function getAdapterNfs(): Collection {

        $listNfs = Http::withToken($this->integrationItem->integration->token)
            ->get($this->integrationItem->integration->url.'/wms/listanotas');

//        $fake = [[
//            "codnota" => 86873,
//            "codempresa" => 1,
//            "codconferencia" => 37563,
//            "codalmoxarifado" => 1,
//            "emitCNPJ" => "61.016.028/0001-01",
//            "dataEmissao" => "2023-05-31T00:00:00",
//            "numero" => 118703,
//            "serie" => "1",
//            "modelo" => "55",
//            "chave" => "35230561016028000101550010001187031000868733",
//            "ambiente" => 1
//        ]];

        if($listNfs->collect()->isEmpty()){
            return $listNfs->collect();
        }

        foreach ($listNfs->collect() as $item) {
            $xml = Http::withToken($this->integrationItem->integration->token)
                ->get($this->integrationItem->integration->url."/wms/notafiscal/{$item['codnota']}/xml");

            $nfs[] = [
                'codconferencia'    => $item['codconferencia'],
                'xmlString'         => $xml->collect()->first(),
                'codnota'           => $item['codnota'],
                'numero'            => $item['numero']
            ];
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
            $adapterNf['codconferencia'],
            $adapterNf['xmlString'],
            $adapterNf['numero'],
            $adapterNf['codnota']
        );
    }

    /**
     * INFORMA SE NECESSARIO QUE A NOTA FOI RECEBIDA
     * @param ImportNfDto $nfDto
     * @return bool
     */
    public function confirmInvoiceImport(ImportNfDto $nfDto): bool {
        $inform = Http::withToken($this->integrationItem->integration->token)
            ->get($this->integrationItem->integration->url."/wms/listanotas/{$nfDto->getThirdSystemCodeNf()}/marca");

        if($inform->failed()){
            return false;
        }

        $this->addLogLine("Informado o recebimento da nota: {$nfDto->getThirdSystemCodeNf()}, no versa.");
        return true;
    }
}
