<?php

namespace App\State\OrderItems;

class StateOrderEntryItemConference extends StateOrderEntryItem {

    public function receiveItem(array $request) {
        return $this->back('Você não pode receber este item',true);
    }

    public function refuseItem(array $request) {
        return $this->back('Você não pode recusar este item',true);
    }

}
