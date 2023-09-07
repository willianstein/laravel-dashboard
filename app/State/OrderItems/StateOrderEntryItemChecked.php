<?php

namespace App\State\OrderItems;

use Illuminate\Http\Request;

class StateOrderEntryItemChecked extends  StateOrderEntryItem {

    public function checkItem(Request $request) {
        return $this->back('Ops, A conferencia deste item terminou',true);
    }

    public function discardItem(Request $request) {
        return $this->back('Ops, A conferencia deste item terminou',true);
    }

}
