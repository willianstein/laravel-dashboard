<?php

namespace App\State\OrderItems;

class StateOrderEntryItemRefused extends StateOrderEntryItem {

    public function receiveItem(array $request) {
        return $this->back('Este pedido já foi recebido',true);
    }

    public function refuseItem(array $request) {
        return $this->back('Este Pedido já foi recebido. Não pode ser recusado',true);
    }

}
