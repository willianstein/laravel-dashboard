<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmRecipientRequest extends FormRequest {
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
            'name'          => 'required|string',
            'document01'    => 'required|string',
            'postal_code'   => 'required|string',
            'address'       => 'required|string',
            'number'        => 'required|string',
            'complement'    => 'nullable|string',
            'neighborhood'  => 'required|string',
            'city'          => 'required|string',
            'state'         => 'required|string',
            'country'       => 'required|string'
        ];
    }

    public function validationData() {
        $data = $this->all();
        $data['document01'] = preg_replace('/[^0-9]/', '', $data['document01']);
        $data['postal_code'] = preg_replace('/[^0-9]/', '', $data['postal_code']);
        return $data;
    }

    public function messages() {
        return [
            'name.required'          => 'Favor preencher o nome',
            'document01.required'    => 'Favor preencher o CPF/CNPJ',
            'postal_code.required'   => 'Favor preencher o CEP',
            'address.required'       => 'Favor preencher o endereço',
            'number.required'        => 'Favor preencher o número',
            'neighborhood.required'  => 'Favor preencher o bairro',
            'city.required'          => 'Favor preencher o cidade',
            'state.required'         => 'Favor preencher o estado',
            'country.required'       => 'Favor preencher o país'
        ];
    }
}
