<?php

namespace App\Http\Adapters\Contracts;

use App\Models\DataTransferObjects\OrderEntryDto;
use App\Models\DataTransferObjects\OrderItemDto;
use App\Models\DataTransferObjects\RecipientDto;
use App\Models\DataTransferObjects\TransportDto;
use App\Models\OrderEntries;
use Illuminate\Support\Collection;
use App\Models\IntegrationItems;

abstract class OrdersEntryAdapter extends OrdersBase {

    /**
     * DA BOOT NO ADAPTADOR
     * @param IntegrationItems $integrationItem
     * @return static
     */
    public abstract static function boot(IntegrationItems $integrationItem): self;

    /**
     * VERIFICA SE O PEDIDO JÁ ESTA CADASTRADO NO EMS
     * @param array $adapterOrder
     * @return bool
     */
    public abstract function hasOrderOnBBEms(array $adapterOrder): bool;

    /**
     * BUSCA OS PEDIDOS NO WEBERVER
     * @return Collection
     */
    public abstract function getAdapterOrders(): Collection;

    /**
     * BUSCA OS ITENS DO PEDIDO NO WEBSERVER
     * @param OrderEntries $orderEntry
     * @param array|null $adapterOrder
     * @return Collection
     */
    public abstract function getAdapterOrderItems(OrderEntries $orderEntry, array $adapterOrder=null): Collection;

    /**
     * CRIA OS DATA TRANSFER OBJECTS DOS PEDIDOS DE SAIDA
     * PARA INSERIR NO EMS
     * @param array $adapterOrder
     * @return orderEntryDto
     */
    public abstract function processOrder(array $adapterOrder): orderEntryDto;

    /**
     * CRIA DATA TRANSFER OBJECTS DOS ITENS DO PEDIDO DE SAIDA
     * @param array $adapterOrderItem
     * @param OrderEntries $orderEntry
     * @return OrderItemDto
     */
    public abstract function processItem(array $adapterOrderItem, OrderEntries $orderEntry): OrderItemDto;

    /**
     * PROCESSA OS DADOS DO DESTINATÁRIO
     * @param array $adapterOrder
     * @return RecipientDto|null
     */
    public abstract function processRecipient(array $adapterOrder): ?RecipientDto;

    /**
     * PROCESSA OS DADOS DO TRANSPORTE
     * @param array $adapterOrder
     * @return TransportDto
     */
    public abstract function processTransport(array $adapterOrder): TransportDto;

}
