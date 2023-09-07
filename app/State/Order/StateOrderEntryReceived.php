<?php

namespace App\State\Order;

use App\Models\OrderItemEntries;

class StateOrderEntryReceived extends StateOrderEntry {

    public function cancelOrder() {
        return $this->back('Ops, Você não pode cancelar este pedido',true);
    }

    public function receiveOrder() {
        return $this->back('Ops, este pedido já foi concluído',true);
    }

    public function addItem(array $request) {
        return $this->back('Ops, Você não incluir um item neste pedido',true);
    }

    public function removeItem(OrderItemEntries $orderItem) {
        return $this->back('Ops, Você não pode remover este item',true);
    }

}
