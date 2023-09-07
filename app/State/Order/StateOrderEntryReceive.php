<?php

namespace App\State\Order;

use App\Models\OrderItems;

class StateOrderEntryReceive extends StateOrderEntry {

    public function receiveOrder() {
        return $this->back('Pedido já foi concluído anteriormente',true);
    }

    public function addItem(array $request) {
        return $this->back('Pedido já foi concluído. Você não pode adicionar um item',true);
    }

    public function removeItem(OrderItems $orderItem) {
        return $this->back('Pedido já foi concluído. Você não pode remover um item',true);
    }

}
