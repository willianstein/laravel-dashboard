<?php

namespace App\Services\Integrations;

use App\Http\Adapters\Contracts\OrdersEntryAdapter;
use App\Http\Adapters\Contracts\OrdersExitAdapter;
use App\Models\IntegrationItems;
use App\Models\OrderEntries;
use App\Models\OrderItemEntries;
use App\Models\Recipients;
use App\Models\Transports;
use Illuminate\Support\Facades\DB;

class OrdersEntryIntegration {

    public static function run() {

        if(!$integrations = IntegrationItems::where('type','order_entries')->get()) {
            return;
        }

        foreach ($integrations as $integration){

            self::getOrders($integration);

        }

    }

    private static function getOrders(IntegrationItems $integrationItem): bool {

        /** @var OrdersExitAdapter $adapter */
        $adapter = ($integrationItem->adapter)::boot($integrationItem);
        $adapter->addLogLine("\nData: ".date('d-m-Y H:i:s'));
        $adapter->addLogLine("Tipo: Pedido de Entrada");
        $adapter->startLog();

        /* Verifica se o Servidor Esta Disponivel */
        if(!$adapter->checkServer()){return false; }

        /* Verifica se há itens à importar */
        $adapterOrders = $adapter->getAdapterOrders();
        if($adapterOrders->isEmpty()){
            $adapter->addLogLine('Não há pedidos para importar');
        }

        foreach ($adapterOrders as $adapterOrder){

            DB::beginTransaction();

            try {

                /* Verifica se o Pedido Já Foi Importado */
                if ($adapter->hasOrderOnBBEms($adapterOrder)) {
                    throw new \Exception('Importação do Pedido Cancelada');
                }

                /* Cria Pedido no EMS */
                $orderEntryDto = $adapter->processOrder($adapterOrder);
                if(!$orderEntry = OrderEntries::create($orderEntryDto->toArray())){
                    $adapter->addLogLine("Pedido {$orderEntryDto->getThirdSystemId()}: Falha ao processar.");
                    throw new \Exception('Importação Cancelada');
                }

                /* Log */
                $adapter->addLogLine("\nProcessando o pedido de entrada: {$orderEntry->third_system_id}");

                /* Insere Itens do Pedido no EMS */
                if(!self::processItems($orderEntry, $adapter, $adapterOrder)){
                    throw new \Exception('Importação do Pedido Cancelada');
                }

                /* Adiciona o Destinatário */
                $recipientDto = $adapter->processRecipient($adapterOrder);
                if(empty($recipientDto) || !$recipient = Recipients::create($recipientDto->toArray())){
                    $adapter->addLogLine("Destinatário: Falhou");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Atualiza Destinatario no Pedido */
                $orderEntry->recipient_id = $recipient->id;
                if(!$orderEntry->save()){
                    $adapter->addLogLine("Destinatário: Falha ao inserir o destinatário no pedido");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Log */
                $adapter->addLogLine("Destinatário: Sucesso");

                /* Adiciona o Transporte */
                $transportDto = $adapter->processTransport($adapterOrder);
                if(!$transport = Transports::create($transportDto->toArray())){
                    $adapter->addLogLine("Transporte: Falhou");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Atualiza Transporte no Pedido */
                $orderEntry->transport_id = $transport->id;
                if(!$orderEntry->save()){
                    $adapter->addLogLine("Transporte: Falha ao inserir a transportadora no pedido");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Log */
                $adapter->addLogLine("Transporte: Sucesso");



            } catch (\Exception $exception) {
                $adapter->addLogLine("Status: {$exception->getMessage()}");
                DB::rollBack();
                continue;
            }

            DB::commit();
        }

        $adapter->endLog();
        return true;

    }

    public static function processItems(OrderEntries $Entry, OrdersEntryAdapter $adapter, array $adapterOrder=null): bool {

        foreach ($adapter->getAdapterOrderItems($Entry, $adapterOrder) as $adapterOrderItem){
            $orderItemDto = $adapter->processItem($adapterOrderItem, $Entry);
            if(!$orderItem = OrderItemEntries::create($orderItemDto->toArray())){
                $adapter->addLogLine("Item do Pedido: O ISBN {$orderItemDto->getIsbn()} falhou");
                return false;
            }
            $adapter->addLogLine("Item do Pedido: O ISBN {$orderItemDto->getIsbn()} sucesso");
        }

        return true;
    }

}
