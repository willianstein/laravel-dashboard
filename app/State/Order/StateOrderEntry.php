<?php

namespace App\State\Order;

use App\Models\History\History;
use App\Models\OrderEntries;
use App\Models\OrderItemEntries;
use App\Models\Products;
use App\Services\Integrations\InformStatus;
use App\State\OrderItems\StateOrderEntryItemConcluded;
use App\State\OrderItems\StateOrderEntryItemConference;
use App\State\OrderItems\StateOrderEntryItemNew;
use App\State\OrderItems\StateOrderEntryItemReceive;

/**
 *  CLASSE STATE DE PEDIDO
 */
abstract class StateOrderEntry {

    /** @var OrderEntries */
    private static OrderEntries $orderEntry;
    /** @var bool */
    private bool $fail;
    /** @var string */
    private string $message;

    /**
     * CONSTRUCT
     * @param OrderEntries $orderEntry
     */
    public function __construct(OrderEntries $orderEntry) {
        self::$orderEntry = $orderEntry;
    }

    /**
     * RETORNA À CLASSE
     * @param string $message
     * @param bool $fail
     * @return $this
     */
    public function back(string $message, bool $fail = false) {
        $this->message = $message;
        $this->fail = $fail;
        return $this;
    }

    /**
     * RETORNA SE HOUVE FALHA
     * @return bool
     */
    public function isFail(): bool {
        return $this->fail;
    }

    /**
     * RETORNA A MENSAGEM
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * RETORNA ORDEM DE ENTRADA
     * @return OrderEntries
     */
    public static function getOrderEntry(): OrderEntries {
        return self::$orderEntry;
    }


    /**
     * ATUALIZA PREVISÃO
     * @param array $arguments
     * @return StateOrderEntry
     * @throws \Exception
     */
    public function updateForecast(array $arguments) {
        extract($arguments);
        $forecast = filter_var($forecast, FILTER_SANITIZE_STRIPPED);
        if(!self::$orderEntry->fill(['forecast'=>$forecast])->save()){
            return $this->back('Não foi possível alterar a previsão',true);
        }
        /* Histórico */
        (new History(self::$orderEntry))->description("Data de previsão alterada com sucesso para: ".date_fmt($forecast,'d/m/Y'))->save();

        return $this->back('Atualização Realizada com Sucesso');
    }

    /**
     * CANCELA O PEDIDO
     * @return $this
     */
    public function cancelOrder() {
        $status = self::$orderEntry->status(StateOrderEntryCanceled::class);
        if(!self::$orderEntry->fill(['status'=>$status])->save()){
            return $this->back('Falha ao cancelar o pedido',true);
        }
        /* Histórico */
        (new History(self::$orderEntry))->description("Cancelou o Pedido")->save();

        return $this->back('Pedido Cancelado com sucesso');
    }

    /**
     * CONCLUI PEDIDO E MANDA PARA AGUARDANDO RECEBIMENTO
     * @return $this
     */
    public function receiveOrder() {
        $status = self::$orderEntry->status(StateOrderEntryReceive::class);
        $items = self::$orderEntry->items;
        if(empty($items->toArray())){
            return $this->back('Para concluir um pedido, precisamos adicionar um ou mais itens',true);
        }
        if(!self::$orderEntry->fill(['status'=>$status])->save()){
            return $this->back('Oops... Falha ao Concluir o Pedido',true);
        }
        if ($items){
            foreach ($items as $item){
                $item->status = OrderItemEntries::status(StateOrderEntryItemReceive::class);
                $item->save();
            }
        }

        /* Histórico */
        (new History(self::$orderEntry))->description("Concluiu o Pedido")->save();

        return $this->back('Pedido concluído com sucesso');
    }

    /**
     * ADICIONAR ITENS AO PEDIDO
     * @param array $request
     * @return $this
     */
    public function addItem(array $request) {
        $request['order_id'] = self::$orderEntry->id;
        $request['isbn'] = (Products::find($request['product_id']))->isbn;
        $request['status'] = OrderItemEntries::status(StateOrderEntryItemNew::class);
        if(!$orderItem = OrderItemEntries::create($request)){
            return $this->back('Ops... Não conseguimos inserir o produto',true);
        }

        /* Histórico */
        (new History(self::$orderEntry))->description("Adicionou o ISBN: {$request['isbn']}")->save();

        return $this->back('Item adicionado com sucesso');
    }

    /**
     * REMOVE ITEM DO PEDIDO
     * @param OrderItemEntries $orderItem
     * @return $this
     */
    public function removeItem(OrderItemEntries $orderItem) {
        $isbn = $orderItem->product->isbn;
        if(!$orderItem->delete()){
            return $this->back('Ops... falha ao remover o item',true);
        }

        /* Histórico */
        (new History(self::$orderEntry))->description("Removeu o ISBN: {$isbn}")->save();

        return $this->back('Item removido com sucesso');
    }

    /**
     * RECEBE O PEDIDO DE ENTRADA
     * @return $this
     */
    public function received() {
        $status = self::$orderEntry->status(StateOrderEntryReceived::class);
        if(self::$orderEntry->hasStatusItems(OrderItemEntries::status(StateOrderEntryItemReceive::class))){
            return $this->back('Você precisa aceitar, ou, recusar, todos os itens',true);
        }
        if(!self::$orderEntry->fill(['status'=>$status])->save()){
            return $this->back('Falha ao receber o pedido',true);
        }
        /* Histórico */
        (new History(self::$orderEntry))->description("Recebeu o Pedido")->save();

        return $this->back('Pedido Recebido com sucesso');
    }

    /**
     * ENVIA PEDIDO PARA CONFERENCIA
     * @return $this
     */
    public function sendToCheck() {

        $status = self::$orderEntry->status(StateOrderEntryConference::class);
        if(self::$orderEntry->hasStatusItems(OrderItemEntries::status(StateOrderEntryItemReceive::class))){
            return $this->back('Você precisa aceitar, ou, recusar, todos os itens',true);
        }
        if(!self::$orderEntry->fill(['status'=>$status])->save()){
            return $this->back('Falha ao Enviar o pedido',true);
        }
        if (self::$orderEntry->items){
            foreach (self::$orderEntry->items as $item){
                $item->status = OrderItemEntries::status(StateOrderEntryItemConference::class);
                $item->save();
            }
        }

        /* Histórico */
        (new History(self::$orderEntry))->description("Enviado Para Conferência")->save();

        /* Informa Sistema do Terceiro (Caso Houver) */
        InformStatus::order(self::$orderEntry);

        return $this->back('Pedido enviado para conferência com sucesso');
    }

    /**
     * CONFERE O PEDIDO E CONCLUI
     * @return $this
     */
    public function checked() {
        $status = self::$orderEntry->status(StateOrderEntryConcluded::class);
        if(self::$orderEntry->hasStatusItems(OrderItemEntries::status(StateOrderEntryItemConference::class))){
            return $this->back('Você precisa concluir a conferência de todos os itens',true);
        }
        if(!self::$orderEntry->fill(['status'=>$status])->save()){
            return $this->back('Falha ao concluir o pedido',true);
        }
        if(self::$orderEntry->items){
            foreach (self::$orderEntry->items as $item){
                $item->status = OrderItemEntries::status(StateOrderEntryItemConcluded::class);
                $item->save();
            }
        }
        /* Histórico */
        (new History(self::$orderEntry))->description("Concluiu o Pedido")->save();

        return $this->back('Pedido Conferido com sucesso');
    }

}
