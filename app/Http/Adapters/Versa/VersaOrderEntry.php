<?php

namespace App\Http\Adapters\Versa;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

use App\Http\Adapters\Contracts\OrdersEntryAdapter;
use App\Models\DataTransferObjects\OrderEntryDto;
use App\Models\DataTransferObjects\OrderItemDto;
use App\Models\DataTransferObjects\ProductDto;
use App\Models\DataTransferObjects\RecipientDto;
use App\Models\DataTransferObjects\TransportDto;
use App\Models\IntegrationItems;
use App\Models\OrderEntries;
use App\Models\Products;
use Throwable;

class VersaOrderEntry extends OrdersEntryAdapter {

    /**
     * DA BOOT NO ADAPTADOR
     * @param IntegrationItems $integrationItem
     * @return static
     */
    public static function boot(IntegrationItems $integrationItem): VersaOrderEntry {
        return new self($integrationItem);
    }

    /**
     * CONTRUTOR
     * @param IntegrationItems $integrationItem
     */
    public function __construct(IntegrationItems $integrationItem) {
        parent::__construct($integrationItem);
    }

    /**
     * VERIFICA SE O PEDIDO JÁ ESTA CADASTRADO NO EMS
     * @param array $adapterOrder
     * @return bool
     */
    public function hasOrderOnBBEms(array $adapterOrder): bool {
        $order = OrderEntries::where('partner_id', $this->integrationItem->integration->partner_id)
            ->where('third_system',     'Versa')
            ->where('third_system_id',  $adapterOrder['codConferencia'])
            ->first();
        if($order){
            $this->addLogLine("\nConferencia de Entrada {$adapterOrder['codConferencia']}: Já importada anteriormente");
            return true;
        }
        return false;
    }

    /**
     * BUSCA OS PEDIDOS NO WEBERVER
     * @return Collection
     */
    public function getAdapterOrders(): Collection {
        $separations = Http::withToken($this->integrationItem->integration->token)
            ->get($this->integrationItem->integration->url.'/wms/listaseparacaoentrada');

        $fake[] = [
            "codConferencia" => 38231,
            "codEmpresa" => 1,
            "codAlmoxarifado" => 1,
            "indAvulso" => 1,
            "indOrigem" => 2,
            "codOrigem" => 0,
            "dscOrigem" => "Compra",
            "nrodocumento" => "12345",
            "datdocumento" => "2023-06-27T00:00:00",
            "datConferencia" => "2023-06-27T15:49:58",
            "razaosocial" => "PARTNER COMERCIAL LTDA.",
            "cnpj" => "04351548000137",
          ];

        if($separations->collect()->isNotEmpty()){
            foreach ($separations->collect() as $separation){
                $order = Http::withToken($this->integrationItem->integration->token)
                    ->get($this->integrationItem->integration->url.'/wms/separacao/'.$separation['codConferencia']);
                $orders[] = $order->collect()->toArray();
            }
        }

        return collect($orders??null);
    }

    /**
     * BUSCA OS ITENS DO PEDIDO NO WEBSERVER
     * @param OrderEntries $orderEntry
     * @param array|null $adapterOrder
     * @return Collection
     */
    public function getAdapterOrderItems(OrderEntries $orderEntry, array $adapterOrder=null): Collection {
        return collect($adapterOrder['itens']);
    }

    /**
     * CRIA OS DATA TRANSFER OBJECTS DOS PEDIDOS DE SAIDA
     * PARA INSERIR NO EMS
     * @param array $adapterOrder
     * @return OrderEntryDto
     */
    public function processOrder(array $adapterOrder): OrderEntryDto {

        $data    = date(now());
        $dataFim = date('Y-m-d 12:00:00', strtotime(now()));

       


        return new OrderEntryDto(
            1,
            $this->integrationItem->integration->partner_id,
            'Novo',
            forecast:  $dataFim < $data ? new \DateTime(date('Y-m-d 23:59:59', strtotime(now(). ' + 2 days'))) :new \DateTime( date('Y-m-d 23:59:59', strtotime(now(). ' + 1 days'))),
            third_system: 'Versa',
            third_system_id: $adapterOrder['codConferencia'],
            observations: addslashes($adapterOrder['observ'])
        );
    }

    /**
     * CRIA DATA TRANSFER OBJECTS DOS ITENS DO PEDIDO DE SAIDA
     * @param array $adapterOrderItem
     * @param OrderEntries $orderExit
     * @return OrderItemDto
     */
    public function processItem(array $adapterOrderItem, OrderEntries $orderExit): OrderItemDto {

        if(!$product = Products::where('isbn',trim($adapterOrderItem['isbn']))->first()){

            $adapterProduct = $product = Http::withToken($this->integrationItem->integration->token)
                ->get($this->integrationItem->integration->url.'/produto/'.$adapterOrderItem['codProduto']);
            $adapterProduct = ($adapterProduct->collect())[0];

            $productDto = new ProductDto(
                trim($adapterProduct['isbn']),
                $adapterProduct['titulo'],
                (int)($adapterProduct['comprimento'] * 10),
                (int)($adapterProduct['largura'] * 10),
                (int)($adapterProduct['altura'] * 10),
                (int)($adapterProduct['peso'] * 1000),
                $adapterProduct['editora'],
                $adapterProduct['assunto'],
                $adapterProduct['sinopse'],
            );
            $product = Products::create($productDto->toArray());
        }

        return new OrderItemDto(
            $orderExit->id,
            $product->id,
            $product->isbn,
            $adapterOrderItem['quantidade'],
            'Novo'
        );
    }

    /**
     * PROCESSA OS DADOS DO DESTINATÁRIO
     * @param array $adapterOrder
     * @return RecipientDto|null
     */
    public function processRecipient(array $adapterOrder): ?RecipientDto {

        try {
            return new RecipientDto(
                $adapterOrder['destNome'],
                clear_number($adapterOrder['destCNPJ']),
                clear_number($adapterOrder['destCEP']),
                $adapterOrder['destLgr'],
                $adapterOrder['destNro'],
                $adapterOrder['destBairro'],
                $adapterOrder['destxMun'],
                $adapterOrder['destxUF'],
                'Brasil',
                $adapterOrder['destCpl'],
            );
        } catch (Throwable $exception) {
            $this->addLogLine("Ops: Dados do destinatário ausente ou, incompletos");
            return null;
        }

    }

    /**
     * PROCESSA OS DADOS DO TRANSPORTE
     * @param array $adapterOrder
     * @return TransportDto
     */
    public function processTransport(array $adapterOrder): TransportDto {

        return new TransportDto(
            'CIF',
            $adapterOrder['transNome']??null,
            $adapterOrder['transCNPJ']??null
        );

    }
}
