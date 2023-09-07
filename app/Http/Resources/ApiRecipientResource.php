<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiRecipientResource extends JsonResource {

    public function toArray($request) {
        return [
            'name'          => $this->name,
            'document01'    => $this->document01,
            'postal_code'   => $this->postal_code,
            'address'       => $this->address,
            'number'        => $this->number,
            'complement'    => $this->complement,
            'neighborhood'  => $this->neighborhood,
            'city'          => $this->city,
            'state'         => $this->state,
            'country'       => $this->country
        ];
    }

}
