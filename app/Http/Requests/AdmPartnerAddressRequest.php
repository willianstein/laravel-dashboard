<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdmPartnerAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'address_id'    => 'nullable',
            'partner_id'    => 'nullable',
            'type'          => 'required|string',
            'address'       => 'required|string',
            'number'        => 'required|string',
            'complement'    => 'nullable|string',
            'neighborhood'  => 'required|string',
            'city'          => 'required|string',
            'state'         => 'required|string|max:2',
            'country'       => 'required|string',
            'postal_code'   => 'required|string|min:8|max:9',
            'active'        => 'nullable|integer'
        ];
    }

    public function validationData() {
        $data = $this->all();
        $data['postal_code'] = preg_replace('/[^0-9]/', '', $data['postal_code']);

        return $data;
    }

    public function messages() {
        return [
            'type.required'         => 'Especifique (Comercial, Fiscal...)',
            'address.required'      => 'Preencha o Endereço',
            'number.required'       => 'Preencha N/D caso não tiver',
            'neighborhood.required' => 'Preencha o Bairro',
            'city.required'         => 'Preencha a Cidade',
            'state.required'        => 'Preencha o Estado',
            'state.max'             => 'Verifique o Estado',
            'country.required'      => 'Preencha o Pais',
            'postal_code.required'  => 'Verifique o CEP',
            'postal_code.min'       => 'Verifique o CEP',
            'postal_code.max'       => 'Verifique o CEP',
        ];
    }
}
