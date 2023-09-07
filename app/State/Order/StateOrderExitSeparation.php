<?php

namespace App\State\Order;

use App\Models\OrderItemExits;
use Illuminate\Http\Request;

class StateOrderExitSeparation extends StateOrderExit {

    public function updateForecast(array $arguments) {
        return $this->back('Pedido em separação. Não é possível altera-lo.',true);
    }

    public function breakApartOrder() {
        return $this->back('Este pedido já foi concluído anteriormente',true);
    }

    public function addItem(Request $request) {
        return $this->back('Pedido em separação. Não é possível adicionar itens.',true);
    }

    public function removeItem(OrderItemExits $orderItem) {
        return $this->back('Pedido em separação. Não é possível remover itens.',true);
    }

}
