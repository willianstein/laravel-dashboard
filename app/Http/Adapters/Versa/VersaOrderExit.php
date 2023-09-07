<?php

namespace App\Http\Adapters\Versa;

use App\Http\Adapters\Contracts\OrdersExitAdapter;
use App\Models\DataTransferObjects\OrderExitDto;
use App\Models\DataTransferObjects\OrderItemDto;
use App\Models\DataTransferObjects\ProductDto;
use App\Models\DataTransferObjects\RecipientDto;
use App\Models\DataTransferObjects\TransportDto;
use App\Models\IntegrationItems;
use App\Models\OrderExits;
use App\Models\Products;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class VersaOrderExit extends OrdersExitAdapter {

    /**
     * DA BOOT NO ADAPTADOR
     * @param IntegrationItems $integrationItem
     * @return static
     */
    public static function boot(IntegrationItems $integrationItem): OrdersExitAdapter {
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
     * VERIFICA STATUS DO SERVER E TOKEN
     * @return bool
     */
    public function checkServer():bool {

        /* Verifica se o Server esta online */
        if(!$link = parent::checkServer()){
            return $link;
        }

        /* Dados Integração */
        $integration = $this->integrationItem->integration;

        /* Verifica Validade Token */
        if(empty($integration->expire_in) || strtotime($integration->expire_in) < strtotime(date('Y-m-d'))){
            /* Cria Token */
            $token = Http::post($integration->url.'/login',[
                "Nome"  => $integration->user,
                "Senha" => $integration->password
            ]);

            /* Verifica se houve erro */
            if($token->failed()){
                $this->addLogLine("Token: Falha ao criar token. Favor checar usuario e senha.");
                return false;
            }

            /* Salva Token */
            $this->addLogLine("Token: Criado com Sucesso.");
            $integration->token = $token->json();
            $integration->expire_in = date('Y-m-d');
            $integration->save();
            return true;
        }

        $this->addLogLine("Token: Dentro da validade");
        return true;

    }

    /**
     * VERIFICA SE O PEDIDO JÁ ESTA CADASTRADO NO EMS
     * @param array $adapterOrder
     * @return bool
     */
    public function hasOrderOnBBEms(array $adapterOrder): bool {
        $order = OrderExits::where('partner_id',       $this->integrationItem->integration->partner_id)
                ->where('third_system',     'Versa')
                ->where('third_system_id',  $adapterOrder['codConferencia'])
                ->first();
        if($order){
            $this->addLogLine("\nConferencia {$adapterOrder['codConferencia']}: Já importada anteriormente");
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
            ->get($this->integrationItem->integration->url.'/wms/listaseparacao');

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
     * @param OrderExits $orderExit
     * @param array|null $adapterOrder
     * @return Collection
     */
    public function getAdapterOrderItems(OrderExits $orderExit, array $adapterOrder=null): Collection {
        return collect($adapterOrder['itens']);
    }

    /**
     * CRIA OS DATA TRANSFER OBJECTS DOS PEDIDOS DE SAIDA
     * PARA INSERIR NO EMS
     * @param array $adapterOrder
     * @return OrderExitDto
     */
    public function processOrder(array $adapterOrder): OrderExitDto {

        $data    = date(now());
        $dataFim = date('Y-m-d 12:00:00', strtotime(now()));

        return new OrderExitDto(
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
     * @param OrderExits $orderExit
     * @return OrderItemDto
     */
    public function processItem(array $adapterOrderItem, OrderExits $orderExit): OrderItemDto {

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
     * @return RecipientDto
     */
    public function processRecipient(array $adapterOrder): RecipientDto {

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
