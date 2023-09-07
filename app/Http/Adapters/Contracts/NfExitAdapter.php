<?php

namespace App\Http\Adapters\Contracts;

use App\Models\DataTransferObjects\ImportNfDto;
use Illuminate\Support\Collection;
use App\Models\IntegrationItems;

abstract class NfExitAdapter extends NfBase {

    /**
     * DA BOOT NO ADAPTADOR
     * @param IntegrationItems $integrationItem
     * @return static
     */
    public abstract static function boot(IntegrationItems $integrationItem): self;

    /**
     * VERIFICA SE A NF JÁ ESTA CADASTRADA NO EMS
     * @param array $adapterNf
     * @return bool
     */
    public abstract function hasNfOnBBEms(array $adapterNf): bool;

    /**
     * VERIFICA SE EXISTEM NOTAS A SEREM IMPORTADAS
     * DEVEMOS RETORNAR UM ARRAY DE ARRAY'S CONTENDO:
     * ['third_system_id' => "iD DO PEDIDO DO TERCEIRO", "xmlString" = "CONTENDO O XML (STRING)"]
     *
     * @return Collection
     */
    public abstract function getAdapterNfs(): Collection;

    /**
     * CRIA OS DATA TRANSFER OBJECTS DAS NOTAS FISCAIS
     * PARA INSERIR NO EMS
     * @param array $adapterNf
     * @return ImportNfDto
     */
    public abstract function processNfs(array $adapterNf): ImportNfDto;

    /**
     * INFORMA SE NECESSARIO QUE A NOTA FOI RECEBIDA
     * @param ImportNfDto $nfDto
     * @return bool
     */
    public abstract function confirmInvoiceImport(ImportNfDto $nfDto): bool;

}
