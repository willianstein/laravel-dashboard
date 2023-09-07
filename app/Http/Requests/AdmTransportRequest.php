<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmTransportRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'modality'          => 'required|string',
            'carrier_name'      => 'nullable|string',
            'packaging'         => 'nullable|string',
            'driver'            => 'nullable|string',
            'driver_document'   => 'nullable|min:11|max:11|string',
            'car_model'         => 'nullable|string',
            'car_type'          => 'nullable|string',
            'car_plate'         => 'nullable|string',
            'transport_id'      => 'nullable|integer'
        ];
    }

    public function validationData() {
        $data = $this->all();
        $data['driver_document'] = preg_replace('/[^0-9]/', '', $data['driver_document']);
        return $data;
    }

    public function messages() {
        return [
            'modality.required'     => 'Favor preencher a Modalidade',
            'driver_document.min'   => 'Favor preencher com o CPF',
            'driver_document.max'   => 'Favor preencher com o CPF'
        ];
    }
}
