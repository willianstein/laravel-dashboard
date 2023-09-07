<?php

namespace App\State\Order;

use App\Services\Integrations\InformConclusionConference;
use App\Services\Integrations\InformOrderExit;
use App\Services\Integrations\InformStatus;
use App\Services\Integrations\InformTracking;
use Illuminate\Http\Request;

use App\State\OrderItems\StateOrderExitItemTransport;
use App\State\OrderItems\StateOrderExitItemConference;
use App\State\OrderItems\StateOrderExitItemSeparation;
use App\State\OrderItems\StateOrderExitItemNew;

use App\Models\Recipients;
use App\Models\Transports;
use App\Models\History\History;
use App\Models\MovementStatus;
use App\Models\OrderExits;
use App\Models\OrderItemExits;
use App\Models\Products;
use App\Models\Stocks;
use Illuminate\Support\Facades\Storage;

abstract class StateOrderExit {

    private static OrderExits $orderExit;

    private bool $fail;

    private string $message;

    public function __construct(OrderExits $orderExit) {
        self::$orderExit = $orderExit;
        $this->fail = false;
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
     * RETORNA ORDEM DE SAIDA
     * @return OrderExits
     */
    public static function getOrderExit(): OrderExits {
        return self::$orderExit;
    }


    /**
     * ATUALIZAR PREVISÃO
     * @param array $arguments
     * @return $this
     * @throws \Exception
     */
    public function updateForecast(array $arguments) {
        extract($arguments);
        $forecast = filter_var($forecast, FILTER_SANITIZE_STRIPPED);
        if(!self::$orderExit->fill(['forecast'=>$forecast])->save()){
            return $this->back('Não foi possível alterar a previsão',true);
        }
        /* Histórico */
        (new History(self::$orderExit))->description("Data de previsão alterada com sucesso para: ".date_fmt($forecast,'d/m/Y'))->save();

        return $this->back('Atualização Realizada com Sucesso');
    }

    /**
     * CANCELA PEDIDO
     * @return $this
     */
    public function cancelOrder() {
        $status = self::$orderExit::status(StateOrderExitCanceled::class);
        if(!self::$orderExit->fill(['status'=>$status])->save()){
            return $this->back('Falha ao cancelar o pedido',true);
        }
        /* Histórico */
        (new History(self::$orderExit))->description(self::$orderExit->status)->save();

        return $this->back('Pedido Cancelado com sucesso');
    }

    /**
     * ADICIONA ITEM AO PEDIDO
     * @param Request $request
     * @return $this
     */
    public function addItem(Request $request) {

        $request['order_id'] = self::$orderExit->id;
        $request['isbn'] = (Products::find($request['product_id']))->isbn;
        $request['status'] = OrderItemExits::status(StateOrderExitItemNew::class);
        $stockCount = Stocks::where('partner_id',self::$orderExit->partner_id)->where('product_id',$request['product_id'])->sum('quantity');

        if($request['quantity'] > $stockCount){
            return $this->back("Ops! Existem apenas {$stockCount} disponíveis",true);
        }

        if(!$orderItem = OrderItemExits::create($request->toArray())){
            return $this->back('Ops... Não conseguimos inserir o produto',true);
        }

        /* Histórico */
        (new History(self::$orderExit))->description("Adicionou o ISBN: {$request['isbn']}")->save();

        return $this->back('Item adicionado com sucesso');
    }

    /**
     * REMOVE ITEM DO PEDIDO
     * @param OrderItemExits $orderItem
     * @return $this
     */
    public function removeItem(OrderItemExits $orderItem) {
        if(!$orderItem->delete()){
            return $this->back('Ops... falha ao remover o item',true);
        }

        /* Histórico */
        (new History(self::$orderExit))->description("Removeu o ISBN: {$orderItem->isbn}")->save();

        return $this->back('Item removido com sucesso');
    }

    /**
     * ENVIA PARA SEPARAÇÃO
     * @return $this
     */
    public function breakApartOrder() {
        $status = self::$orderExit::status(StateOrderExitSeparation::class);
        $items = self::$orderExit->items;

        if(empty($items->toArray())){
            return $this->back('Para concluir um pedido, precisamos adicionar um ou mais itens',true);
        }

        if(empty(self::$orderExit->recipient)){
            return $this->back('Para concluir um pedido, precisamos adicionar um destinatário',true);
        }

        if(empty(self::$orderExit->transport)){
            return $this->back('Para concluir um pedido, precisamos adicionar os dados do transporte',true);
        }

        if(!self::$orderExit->fill(['status'=>$status])->save()){
            return $this->back('Falha ao cancelar o pedido',true);
        }

        if ($items){
            foreach ($items as $item){
                $item->status = OrderItemExits::status(StateOrderExitItemSeparation::class);
                $item->save();
            }
        }

        /* Histórico */
        (new History(self::$orderExit))->description(self::$orderExit->status)->save();

        /* Informa Recebimento do Pedido (Quando existir integração) */
        InformOrderExit::orderExit(self::$orderExit);

        /* Informa Sistema do Terceiro (Caso Houver) */
        InformStatus::order(self::$orderExit);

        return $this->back('Pedido Concluído com sucesso');
    }

    /**
     * ADICIONA DESTINATÁRIO
     * @param Request $request
     * @return $this
     */
    public function addRecipient(Request $request) {
        if(!$recipient = Recipients::updateOrCreate(['document01'=>($request->document01??0)],$request->toArray())){
            return $this->back('Falha ao salvar o destinatário',true);
        }
        self::$orderExit->recipient_id = ($recipient->id??null);
        self::$orderExit->save();
        return $this->back('Destinatário salvo com sucesso');
    }

    /**
     * ADICIONA TRANSPORTE
     * @param Request $request
     * @return $this
     */
    public function addTransport(Request $request) {
        if(!$transport = Transports::updateOrCreate(['id'=>$request['transport_id']],$request->toArray())){
            return $this->back('Falha ao salvar o transporte',true);
        }
        self::$orderExit->transport_id = ($transport->id??null);
        self::$orderExit->save();
        return $this->back('Transporte salvo com sucesso');
    }

    /**
     * ENVIA PARA CONFERENCIA
     * @return $this
     */
    public function sendToConference() {

        $status = self::$orderExit::status(StateOrderExitConference::class);
        $items = self::$orderExit->items;

        foreach ($items as $item){
            if($item->status == OrderItemExits::status(StateOrderExitItemSeparation::class)){
                return $this->back('Antes de concluir, precisamos separar todos os itens',true);
            }
        }

        if(!self::$orderExit->fill(['status'=>$status])->save()){
            return $this->back('Falha ao concluir o pedido',true);
        }

        if ($items){
            foreach ($items as $item){
                $item->status = OrderItemExits::status(StateOrderExitItemConference::class);
                $item->save();
            }
        }

        /* Histórico */ //TODO ajustar para pegar certo
        (new History(self::$orderExit))->description(self::$orderExit->status)->save();

        /* Informa Sistema do Terceiro (Caso Houver) */
        // InformStatus::order(self::$orderExit);

        return $this->back('Pedido enviado para conferência');

    }

    /**
     * CONCLUI A CONFERENCIA
     * @return $this
     */
    public function checked() {
        $status = self::$orderExit->status(StateOrderExitTransport::class);
        if(self::$orderExit->hasStatusItems(OrderItemExits::status(StateOrderExitItemConference::class))){
            return $this->back('Você precisa concluir a conferência de todos os itens',true);
        }
        if(!self::$orderExit->fill(['status'=>$status])->save()){
            return $this->back('Falha ao concluir o pedido',true);
        }
        if(self::$orderExit->items){
            foreach (self::$orderExit->items as $item){
                $item->status = OrderItemExits::status(StateOrderExitItemTransport::class);
                $item->save();
            }
        }
        /* Histórico */
        (new History(self::$orderExit))->description(self::$orderExit->status)->save();

        /* Informa Conclusão da Conferencia (Se houver integração) */
        InformConclusionConference::orderExit(self::$orderExit);

        /* Informa Rastreio (Se houver integração) */
        InformTracking::orderExit(self::$orderExit);

        return $this->back('Pedido Conferido com sucesso');
    }

    /**
     * ATUALIZA TRANSPORTE
     * @param Request $request
     * @return void
     */
    public function updateTransport(Request $request) {

        $transport = self::$orderExit->transport;
        if(!$transport->fill($request->toArray())->save()){
            return $this->back('Ops, falha ao atualizar os dados de transporte', true);
        }

        return $this->back('Transporte atualizado com sucesso');
    }

    /**
     * CONCLUIR O PEDIDO
     * @return void
     */
    public function complete() {

        /* Verifica se todos itens estão concluídos */
        if(self::$orderExit->hasStatusItems(OrderItemExits::status(StateOrderExitItemTransport::class))){
            return $this->back('Você precisa embarcar todos os itens',true);
        }

        /* Verifica as informações de transporte */
        if(!self::$orderExit->transport->registrationIsComplete()){
            return $this->back('Você precisa preencher as informações do transporte',true);
        }

        $status = self::$orderExit::status(StateOrderExitConcluded::class);
        if(!self::$orderExit->fill(['status'=>$status])->save()){
            return $this->back('Falha ao concluir o pedido',true);
        }

        /* Histórico */
        (new History(self::$orderExit))->description(self::$orderExit->status)->save();

        return $this->back('Pedido Concluído com sucesso');
    }

    /**
     * ADICIONAR NFE AO PEDIDO
     * @param Request $request
     * @return $this
     */
    public function addNfe(Request $request) {

        if(!empty(self::$orderExit->invoice)){
            Storage::disk('local')->delete(self::$orderExit->invoice);
        }

        $nfe = $request->file('nfe')->storeAs(
            'NFe', "NF-Pedido-".self::$orderExit->id,'local'
        );

        self::$orderExit->invoice = $nfe;
        self::$orderExit->save();

        /* Histórico */
        (new History(self::$orderExit))->description("Adicionou uma Nota Fiscal")->save();

        return $this->back('Nota adicionada com sucesso');
    }

}
