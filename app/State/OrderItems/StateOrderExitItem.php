<?php

namespace App\State\OrderItems;

use App\Models\History\History;
use App\Models\OrderItemExits;

class StateOrderExitItem {

    /** @var OrderItemExits */
    private static OrderItemExits $orderItemExit;
    /** @var bool */
    private bool $fail;
    /** @var string */
    private string $message;

    /**
     * CONSTRUCT
     * @param OrderItemExits $orderItemExit
     */
    public function __construct(OrderItemExits $orderItemExit) {
        self::$orderItemExit = $orderItemExit;
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
     * @return OrderItemExits
     */
    public static function getOrderItem(): OrderItemExits {
        return self::$orderItemExit;
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
     * CONCLUIR O RECEBIMENTO DE UM ITEM
     * @param array $request
     * @return $this
     */
    public function completeItem(array $request) {
        $status = self::$orderItemExit->status(StateOrderExitItemComplete::class);

        if(!self::$orderItemExit->fill(['status'=>$status])->save()){
            return $this->back('Falha ao receber o item',true);
        }
        /* Histórico */
        (new History(self::$orderItemExit))->description("Recebeu o ISBN: ".self::$orderItemExit->isbn)->notes($request['notes'])->save();

        return $this->back('Item do Pedido Recebido com sucesso');
    }

}
