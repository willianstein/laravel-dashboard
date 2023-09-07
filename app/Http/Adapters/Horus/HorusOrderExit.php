<?php

namespace App\Http\Adapters\Horus;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

use App\Http\Adapters\Contracts\OrdersExitAdapter;

use App\Models\Products;
use App\Models\IntegrationItems;
use App\Models\OrderExits;
use App\Models\DataTransferObjects\OrderItemDto;
use App\Models\DataTransferObjects\ProductDto;
use App\Models\DataTransferObjects\RecipientDto;
use App\Models\DataTransferObjects\TransportDto;
use App\Models\DataTransferObjects\OrderExitDto;

/**
 *  ADAPTADOR PARA COMUNICAÇÃO COM O ERP HORUS
 */
class HorusOrderExit extends OrdersExitAdapter {

    /**
     * BOOT DO ADAPTADOR
     * @param IntegrationItems $integrationItem
     * @return OrdersExitAdapter
     */
    public static function boot(IntegrationItems $integrationItem): OrdersExitAdapter {
        return new self($integrationItem);
    }

    /**
     * @param IntegrationItems $integrationItem
     */
    public function __construct(IntegrationItems $integrationItem) {
        parent::__construct($integrationItem);
    }

    /**
     * VERIFICA SE O PEDIDO EXISTE NO EMS
     * @param array $adapterOrder
     * @return bool
     */
    public function hasOrderOnBBEms(array $adapterOrder): bool {
        $order =  OrderExits::where('partner_id',       $this->integrationItem->integration->partner_id)
                            ->where('third_system',     'Horus')
                            ->where('third_system_id',  $adapterOrder['COD_PED_VENDA'])
                            ->first();
        if($order){
            $this->addLogLine("\nPedido {$adapterOrder['COD_PED_VENDA']}: Já importado anteriormente");
            return true;
        }
        return false;
    }

    /**
     * BUSCA OS PEDIDOS NO WEBSERVER HORUS
     * @return Collection
     */
    public function getAdapterOrders(): Collection {
        $params = json_decode($this->integrationItem->params,true);
        $params['STA_PEDIDO'] = 'LEX';
        $orders = Http::withBasicAuth($this->integrationItem->integration->user,$this->integrationItem->integration->password)
                ->get($this->integrationItem->integration->url.'/Busca_PedidosVenda',$params);

        $response = $orders->collect()->first();
        if(!empty($response['Falha'])){
            $this->addLogLine("Ao obter pedidos: {$response['Falha']}. {$response['Mensagem']}");
            return collect([]);
        }

        return $orders->collect();
    }

    /**
     * BUSCA OS ITENS DO PEDIDO NO WEB SERVER HORUS
     * @param OrderExits $orderExit
     * @return Collection
     */
    public function getAdapterOrderItems(OrderExits $orderExit, array $adapterOrder=null): Collection {
        $params = json_decode($this->integrationItem->params,true);
        $params['COD_PED_VENDA'] = $orderExit->third_system_id;
        $items = Http::withBasicAuth($this->integrationItem->integration->user,$this->integrationItem->integration->password)
            ->get($this->integrationItem->integration->url.'/Busca_ItensPedidosVenda',$params);
        return $items->collect();
    }

    /**
     * BUSCA ACERVO PELO CODIGO
     * @param string $COD_ITEM
     * @return Collection
     */
    public function getProduct(string $COD_ITEM): Collection {
        $product = Http::withBasicAuth($this->integrationItem->integration->user,$this->integrationItem->integration->password)
                 ->get($this->integrationItem->integration->url.'/Busca_Acervo',['COD_ITEM' => $COD_ITEM]);
        return $product->collect();
    }

    /**
     * CRIA O DATA TRANSFER OBJECT PARA INSERÇÃO DO PEDIDO
     * @param array $adapterOrder
     * @return OrderExitDto
     * @throws \Exception
     */
    public function processOrder(array $adapterOrder): OrderExitDto {

        $data    = date(now());
        $dataFim = date('Y-m-d 12:00:00', strtotime(now()));

        return new OrderExitDto(
            1,
            $this->integrationItem->integration->partner_id,
            'Novo',
            forecast:  $dataFim < $data ? new \DateTime(date('Y-m-d 23:59:59', strtotime(now(). ' + 2 days'))) :new \DateTime( date('Y-m-d 23:59:59', strtotime(now(). ' + 1 days'))),
            third_system: 'Horus',
            third_system_id: $adapterOrder['COD_PED_VENDA']
        );
    }

    /**
     * CRIA DATA TRANSFER OBJECTS DOS ITENS DO PEDIDO DE SAIDA
     * @param array $adapterOrderItem
     * @param OrderExits $orderExit
     * @return OrderItemDto
     */
    public function processItem(array $adapterOrderItem, OrderExits $orderExit): OrderItemDto {

        /* Busca Titulo no WebServer */
        $adapterProduct = ($this->getProduct($adapterOrderItem['COD_ITEM']))[0];

        /* Verifica se há cadastro no EMS */
        if(!$product = Products::where('isbn',$adapterProduct['COD_BARRA_ITEM'])->first()){
            $productDto = new ProductDto(
                $adapterProduct['COD_BARRA_ITEM'],
                $adapterProduct['NOM_ITEM'],
                (int)($adapterProduct['ALTURA_ITEM'] * 10),
                (int)($adapterProduct['LARGURA_ITEM'] * 10),
                (int)($adapterProduct['COMPRIMENTO_ITEM'] * 10),
                (int)$adapterProduct['PESO_ITEM'],
                $adapterProduct['NOM_EDITORA'],
                $adapterProduct['GENERO_NIVEL_1'],
                $adapterProduct['DESC_SINOPSE'],
            );
            $product = Products::create($productDto->toArray());
        }

        return new OrderItemDto(
            $orderExit->id,
            $product->id,
            $product->isbn,
            $adapterOrderItem['QT_PEDIDA'],
            'Novo'
        );
    }

    /**
     * PROCESSA OS DADOS DO DESTINATÁRIO
     * @param array $adapterOrder
     * @return RecipientDto
     */
    public function processRecipient(array $adapterOrder): RecipientDto {

        $recipient  = Http::withBasicAuth($this->integrationItem->integration->user,$this->integrationItem->integration->password)
                    ->get($this->integrationItem->integration->url.'/Busca_Cliente',['COD_CLI' => $adapterOrder['COD_CLI']]);
        $recipient = $recipient->collect()->first();

        $recipientAddress   = Http::withBasicAuth($this->integrationItem->integration->user,$this->integrationItem->integration->password)
                            ->get($this->integrationItem->integration->url.'/Busca_EndCliente',['COD_CLI' => $adapterOrder['COD_CLI']]);
        $recipientAddress = $recipientAddress->collect()->first();

        return new RecipientDto(
            $recipient['NOM_CLI'],
            ($recipient['CNPJ'] ?: $recipient['CPF']),
            $recipientAddress['CEP'],
            $recipientAddress['DESC_ENDERECO'],
            $recipientAddress['NUM_END'] ?? "SN",
            $recipientAddress['NOM_BAIRRO'],
            $recipientAddress['NOME_UF'],
            $recipientAddress['SIGLA_UF'],
            'Brasil',
            $recipientAddress['COM_ENDERECO']
        );
    }

    /**
     * PROCESSA OS DADOS DO TRANSPORTE
     * @param array $adapterOrder
     * @return TransportDto
     */
    public function processTransport(array $adapterOrder): TransportDto {
        $params = json_decode($this->integrationItem->params, true);

        $transports = Http::withBasicAuth($this->integrationItem->integration->user,$this->integrationItem->integration->password)
                    ->get($this->integrationItem->integration->url.'/Busca_Transportadora',$params);

        foreach ($transports->collect() as $transport){
            if(!empty($transport['COD_TRANSP']) && $transport['COD_TRANSP'] == $adapterOrder['COD_TRANSP']){
                $transportName = $transport['NOM_TRANSP'];
            }
        }

        return new TransportDto(
            'CIF',
            ($transportName??null)
        );
    }
}
