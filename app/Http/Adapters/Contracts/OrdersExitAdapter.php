<?php

namespace App\Http\Adapters\Contracts;

use App\Models\DataTransferObjects\OrderItemDto;
use App\Models\DataTransferObjects\ProductDto;
use App\Models\DataTransferObjects\RecipientDto;
use App\Models\DataTransferObjects\TransportDto;
use App\Models\OrderExits;
use Illuminate\Support\Collection;

use App\Models\IntegrationItems;
use App\Models\DataTransferObjects\OrderExitDto;

/**
 * CONTRATO DOS ADAPTADORES, PARA INSERÇÃO
 * DE PEDIDOS DE SAIDA NO EMS
 */
abstract class OrdersExitAdapter extends OrdersBase {

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
     * @param OrderExits $orderExit
     * @param array|null $adapterOrder
     * @return Collection
     */
    public abstract function getAdapterOrderItems(OrderExits $orderExit, array $adapterOrder=null): Collection;

    /**
     * CRIA OS DATA TRANSFER OBJECTS DOS PEDIDOS DE SAIDA
     * PARA INSERIR NO EMS
     * @param array $adapterOrder
     * @return OrderExitDto
     */
    public abstract function processOrder(array $adapterOrder): OrderExitDto;

    /**
     * CRIA DATA TRANSFER OBJECTS DOS ITENS DO PEDIDO DE SAIDA
     * @param array $adapterOrderItem
     * @param OrderExits $orderExit
     * @return OrderItemDto
     */
    public abstract function processItem(array $adapterOrderItem, OrderExits $orderExit): OrderItemDto;

    /**
     * PROCESSA OS DADOS DO DESTINATÁRIO
     * @param array $adapterOrder
     * @return RecipientDto
     */
    public abstract function processRecipient(array $adapterOrder): RecipientDto;

    /**
     * PROCESSA OS DADOS DO TRANSPORTE
     * @param array $adapterOrder
     * @return TransportDto
     */
    public abstract function processTransport(array $adapterOrder): TransportDto;

}
