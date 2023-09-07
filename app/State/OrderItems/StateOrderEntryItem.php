<?php

namespace App\State\OrderItems;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

use App\Models\History\History;
use App\Models\OrderItemAttachments;
use App\Models\OrderItemEntries;
use App\Models\Stocks;

abstract class StateOrderEntryItem {

    /** @var OrderItemEntries */
    private static OrderItemEntries $orderItemEntry;
    /** @var bool */
    private bool $fail;
    /** @var string */
    private string $message;

    /**
     * CONSTRUCT
     * @param OrderItemEntries $orderItemEntry
     */
    public function __construct(OrderItemEntries $orderItemEntry) {
        self::$orderItemEntry = $orderItemEntry;
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
     * RETORNA O ITEM
     * @return OrderItemEntries
     */
    public static function getOrderItem(): OrderItemEntries {
        return self::$orderItemEntry;
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
     * RECEBE UM ITEM DO PEDIDO
     * @param array $request
     * @return $this
     */
    public function receiveItem(array $request) {
        $status = self::$orderItemEntry->status(StateOrderEntryItemReceived::class);
        if(!self::$orderItemEntry->fill(['status'=>$status])->save()){
            return $this->back('Falha ao receber o item',true);
        }
        /* Histórico */
        (new History(self::$orderItemEntry))->description("Recebeu o ISBN: ".self::$orderItemEntry->isbn)->notes($request['notes'])->save();

        return $this->back('Item do Pedido Recebido com sucesso');
    }

    /**
     * RECUSA UM ITEM DO PEDIDO
     * @param array $request
     * @return $this
     */
    public function refuseItem(array $request) {
        $status = self::$orderItemEntry->status(StateOrderEntryItemRefused::class);
        if(!self::$orderItemEntry->fill(['status'=>$status])->save()){
            return $this->back('Falha ao recusar o item',true);
        }
        /* Histórico */
        (new History(self::$orderItemEntry))->description("Recusou o ISBN: ".self::$orderItemEntry->isbn)->notes($request['notes'])->save();

        return $this->back('Item do Pedido Recusado com sucesso');
    }

    /**
     * ENDEREÇA E CONFERE O ITEM
     * @param Request $request
     * @return $this
     */
    public function checkItem(Request $request) {

        /* Checa Disponibilidade */
        if($request->quantity > (self::$orderItemEntry->quantity - self::$orderItemEntry->checked - self::$orderItemEntry->discarded)){
            return $this->back('Ops, quantidade conferida superior ao disponível',true);
        }

        /* Checa Endereçamento */
        if(empty($request->stock_id)){
            return $this->back('Ops, é necessário informar um endereçamento.',true);
        }

        /* Carrega Endereço */
        if(!$stock = Stocks::find((int) $request->stock_id)){
            return $this->back('Ops, endereçamento não localizado.',true);
        }

        /* Atualiza Item */
        self::$orderItemEntry->checked += $request->quantity;
        if(!self::$orderItemEntry->save()){
            return $this->back('Ops, falha ao conferir item.',true);
        }

        /* Atualiza Stock */
        $stock->quantity += $request->quantity;
        if(!$stock->save()){
            return $this->back('Ops, falha ao contabilizar item.',true);
        }

        /* Salva histórico do item */
        (new History(self::$orderItemEntry))->description(
            "Endereçou {$request->quantity} itens do ISBN: ".self::$orderItemEntry->isbn.", no endereço: {$stock->addressing->name}"
        )->save();

        /* Verifica se a contagem esta completa */
        if($this->isChecked()){

            self::$orderItemEntry->status = self::$orderItemEntry::status(StateOrderEntryItemChecked::class);
            self::$orderItemEntry->save();

            (new History(self::$orderItemEntry))->description(
                "Concluiu a conferencia do ISBN: ".self::$orderItemEntry->isbn."."
            )->save();

            return $this->back('Conferência do item terminada');
        }

        return $this->back('Item Endereçado com sucesso');
    }

    /**
     * ENDEREÇA E DESCARTA O ITEM
     * @param Request $request
     * @return $this
     */
    public function discardItem(Request $request) {

        /* Checa Disponibilidade */
        if($request->quantity > (self::$orderItemEntry->quantity - self::$orderItemEntry->checked - self::$orderItemEntry->discarded)){
            return $this->back('Ops, quantidade conferida superior ao disponível',true);
        }

        /* Checa Endereçamento */
        if(empty($request->stock_id)){
            return $this->back('Ops, é necessário informar um endereçamento.',true);
        }

        /* Carrega Endereço */
        if(!$stock = Stocks::find((int) $request->stock_id)){
            return $this->back('Ops, endereçamento não localizado.',true);
        }

        /* Anexa Arquivos */
        if(!$this->attachToItem($request->attachment)){
            return $this->back('Ops, falha ao anexar um item.',true);
        }

        /* Atualiza Item */
        self::$orderItemEntry->discarded += $request->quantity;
        if(!self::$orderItemEntry->save()){
            return $this->back('Ops, falha ao conferir item.',true);
        }

        /* Atualiza Stock */
        $stock->quantity += $request->quantity;
        if(!$stock->save()){
            return $this->back('Ops, falha ao contabilizar item.',true);
        }

        /* Salva histórico do item */
        (new History(self::$orderItemEntry))->description(
            "Descartou {$request->quantity} itens do ISBN: ".self::$orderItemEntry->isbn.", no endereço: {$stock->addressing->name}"
        )->save();

        /* Verifica se a contagem esta completa */
        if($this->isChecked()){

            self::$orderItemEntry->status = self::$orderItemEntry::status(StateOrderEntryItemChecked::class);
            self::$orderItemEntry->save();

            (new History(self::$orderItemEntry))->description(
                "Concluiu a conferencia do ISBN: ".self::$orderItemEntry->isbn."."
            )->save();

            return $this->back('Conferência do item terminada');
        }

        return $this->back('Item Descartado com sucesso');
    }

    /**
     * VERIFICA SE A CONTAGEM ESTA COMPLETA
     * @return bool
     */
    private function isChecked():bool {
        if(self::$orderItemEntry->quantity == (self::$orderItemEntry->checked + self::$orderItemEntry->discarded)){
            return true;
        }
        return false;
    }

    /**
     * ANEXA ARQUIVO NO ITEM DO PEDIDO
     * @param array|UploadedFile $attachments
     * @return bool
     */
    private function attachToItem($attachments): bool {

        if(empty($attachments[0])){
            return true;
        }

        if(!is_array($attachments)){
            $attachment = $attachments->store('order_items','public');
            if(OrderItemAttachments::create([
                'order_id'      => self::$orderItemEntry->order_id,
                'order_item_id' => self::$orderItemEntry->id,
                'attachment'    => $attachment
            ])){
                return true;
            }
        }

        if(is_array($attachments)){
            $flawed = 0;
            foreach ($attachments as $attachment){
                $attachment = $attachment->store('order_items','public');
                if(!OrderItemAttachments::create([
                    'order_id'      => self::$orderItemEntry->order_id,
                    'order_item_id' => self::$orderItemEntry->id,
                    'attachment'    => $attachment
                ])){
                    /* Se Falhar */
                    $flawed += 1;
                }
            }
            if(!$flawed){
                return true;
            }
        }
        return false;
    }


}
