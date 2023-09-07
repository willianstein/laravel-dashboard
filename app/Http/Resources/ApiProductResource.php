<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiProductResource extends JsonResource {

    public $additional = [
        'type' => 'success',
        'message' => 'Sucesso'
    ];

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {

        return [
            'id'        => $this->id,
            'isbn'      => $this->isbn,
            'title'     => $this->title,
            'publisher' => $this->publisher,
            'category'  => $this->category,
            'synopsis'  => $this->synopsis,
            'height'    => $this->height,
            'width'     => $this->width,
            'length'    => $this->length,
            'weight'    => $this->weight,
            'cover'     => (
                !empty($this->cover) ? Storage::disk('public')->url($this->cover) : null
            ),
        ];

    }
}
