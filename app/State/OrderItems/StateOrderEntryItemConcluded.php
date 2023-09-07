<?php

namespace App\State\OrderItems;

use Illuminate\Http\Request;

class StateOrderEntryItemConcluded extends StateOrderEntryItem {

    public function checkItem(Request $request) {
        return $this->back('Este item já foi concluído',true);
    }

    public function discardItem(Request $request) {
        return $this->back('Este item já foi concluído',true);
    }

}
