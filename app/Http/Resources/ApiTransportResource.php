<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiTransportResource extends JsonResource {

    public function toArray($request) {
        return [
            'modality'          => $this->modality,
            'carrier_name'      => $this->carrier_name,
            'packaging'         => $this->packaging,
            'driver'            => $this->driver,
            'driver_document'   => $this->driver_document,
            'car_model'         => $this->car_model,
            'car_type'          => $this->car_type,
            'car_plate'         => $this->car_plate
        ];
    }

}
