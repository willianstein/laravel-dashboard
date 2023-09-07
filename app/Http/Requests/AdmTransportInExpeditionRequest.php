<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmTransportInExpeditionRequest extends FormRequest {
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
            'carrier_name'      => 'required|string',
            'packaging'         => 'required|string',
            'driver'            => 'required|string',
            'driver_document'   => 'required|min:11|max:11|string',
            'car_model'         => 'required|string',
            'car_type'          => 'required|string',
            'car_plate'         => 'required|string'
        ];
    }

    public function validationData() {
        $data = $this->all();
        $data['driver_document'] = preg_replace('/[^0-9]/', '', $data['driver_document']);
        return $data;
    }

    public function messages() {
        return [
            'modality.required'         => 'Favor preencher a Modalidade',
            'carrier_name.required'     => 'Favor preencher a Transportadora',
            'packaging.required'        => 'Favor preencher o Acondicionamento',
            'driver.required'           => 'Favor preencher o Motorista',
            'driver_document.required'  => 'Favor preencher o CPF',
            'driver_document.min'       => 'Favor preencher verifique o CPF',
            'driver_document.max'       => 'Favor preencher verifique o CPF',
            'car_model.required'        => 'Favor preencher o Carro',
            'car_type.required'         => 'Favor preencher o Tipo',
            'car_plate.required'        => 'Favor preencher a Placa'
        ];
    }
}
