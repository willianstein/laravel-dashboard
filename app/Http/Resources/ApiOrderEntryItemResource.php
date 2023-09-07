<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiOrderEntryItemResource extends JsonResource {

    public function toArray($request) {
        return [
            'id'        => $this->id,
            'product'   => new ApiProductResource($this->product),
            'quantity'  => $this->quantity,
            'checked'   => $this->checked,
            'discarded' => $this->discarded,
            'status'    => $this->status,
        ];
    }

}
