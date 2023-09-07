<?php

namespace App\State\Order;

use App\Models\OrderItems;

class StateOrderEntryCanceled extends StateOrderEntry {

    public function updateForecast(array $arguments) {
        return $this->back('Pedido cancelado. Você não pode atualizar a previsão',true);
    }

    public function cancelOrder() {
        return $this->back('Pedido já foi cancelado anteriormente',true);
    }

    public function receiveOrder() {
        return $this->back('Pedido cancelado. Você não pode conclui-lo',true);
    }

    public function addItem(array $request) {
        return $this->back('Pedido cancelado. Você não pode adicionar um item',true);
    }

    public function removeItem(OrderItems $orderItem) {
        return $this->back('Pedido cancelado. Você não pode remover um item',true);
    }

}
