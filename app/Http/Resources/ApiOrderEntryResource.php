<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiOrderEntryResource extends JsonResource {

    public function toArray($request) {
        return [
            'type'              => $this->type,
            'transport'         => ApiTransportResource::make($this->transport),
            'forecast'          => $this->forecast,
            'status'            => $this->status,
            'third_system'      => $this->third_system,
            'third_system_id'   => $this->third_system_id,
            'items'             => ApiOrderEntryItemResource::collection($this->items)
        ];
    }

}
