<?php

namespace App\Services\Integrations;

use App\Models\OrderItemExits;
use App\Models\Recipients;
use App\Models\Transports;
use Illuminate\Support\Facades\DB;

use App\Http\Adapters\Contracts\OrdersExitAdapter;
use App\Models\History\History;
use App\Models\IntegrationItems;
use App\Models\MovementStatus;
use App\Models\OrderExits;
use App\State\Order\StateOrderExitNew;

class OrdersExitIntegration
{
    private static OrderExits $orderExit;

    public static function run()
    {

        if (!$integrations = IntegrationItems::where('type', 'order_exits')->get()) {
            return;
        }

        foreach ($integrations as $integration) {

            self::getOrders($integration);
        }
    }

    public static function getOrders(IntegrationItems $integrationItem): bool
    {

        /** @var OrdersExitAdapter $adapter */
        $adapter = ($integrationItem->adapter)::boot($integrationItem);
        $adapter->addLogLine("\nData: " . date('d-m-Y H:i:s'));
        $adapter->addLogLine("Tipo: Pedido de Saida");
        $adapter->startLog();

        /* Verifica se o Servidor Esta Disponivel */
        if (!$adapter->checkServer()) {
            return false;
        }

        /* Verifica se há itens à importar */
        $adapterOrders = $adapter->getAdapterOrders();
        if ($adapterOrders->isEmpty()) {
            $adapter->addLogLine('Não há pedidos para importar');
        }

        foreach ($adapterOrders as $adapterOrder) {

            DB::beginTransaction();

            try {

                /* Verifica se o Pedido Já Foi Importado */
                if ($adapter->hasOrderOnBBEms($adapterOrder)) {
                    throw new \Exception('Importação do Pedido Cancelada');
                }

                /* Cria Pedido no EMS */
                $orderExitDto = $adapter->processOrder($adapterOrder);
                if (!$orderExit = OrderExits::create($orderExitDto->toArray())) {
                    $adapter->addLogLine("Pedido {$orderExitDto->getThirdSystemId()}: Falha ao processar.");
                    throw new \Exception('Importação Cancelada');
                }

                /* Log */
                $adapter->addLogLine("\nProcessando o pedido: {$orderExit->third_system_id}");

                /* Insere Itens do Pedido no EMS */
                if (!self::processItems($orderExit, $adapter, $adapterOrder)) {
                    throw new \Exception('Importação do Pedido Cancelada');
                }

                /* Adiciona o Destinatário */
                $recipientDto = $adapter->processRecipient($adapterOrder);
                if (!$recipient = Recipients::create($recipientDto->toArray())) {
                    $adapter->addLogLine("Destinatário: Falhou");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Atualiza Destinatario no Pedido */
                $orderExit->recipient_id = $recipient->id;
                if (!$orderExit->save()) {
                    $adapter->addLogLine("Destinatário: Falha ao inserir o destinatário no pedido");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Log */
                $adapter->addLogLine("Destinatário: Sucesso");

                /* Adiciona o Transporte */
                $transportDto = $adapter->processTransport($adapterOrder);
                if (!$transport = Transports::create($transportDto->toArray())) {
                    $adapter->addLogLine("Transporte: Falhou");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Atualiza Transporte no Pedido */
                $orderExit->transport_id = $transport->id;
                if (!$orderExit->save()) {
                    $adapter->addLogLine("Transporte: Falha ao inserir a transportadora no pedido");
                    throw new \Exception("Importação do Pedido Cancelada");
                }

                /* Histórico */
                (new History($orderExit))->description($orderExit::status(StateOrderExitNew::class))->save();

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

    public static function processItems(OrderExits $orderExit, OrdersExitAdapter $adapter, array $adapterOrder = null): bool
    {

        foreach ($adapter->getAdapterOrderItems($orderExit, $adapterOrder) as $adapterOrderItem) {
            $orderItemDto = $adapter->processItem($adapterOrderItem, $orderExit);
            if (!$orderItem = OrderItemExits::create($orderItemDto->toArray())) {
                $adapter->addLogLine("Item do Pedido: O ISBN {$orderItemDto->getIsbn()} falhou");
                return false;
            }
            $adapter->addLogLine("Item do Pedido: O ISBN {$orderItemDto->getIsbn()} sucesso");
        }

        return true;
    }
}
