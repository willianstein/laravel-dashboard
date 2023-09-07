<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdmPartnerRequest extends FormRequest {
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
            'partner_id'    => 'nullable',
            'name'          => 'required|string',
            'trade_name'    => 'nullable|string',
            'person'        => Rule::in(['fisica','juridica']),
            'document01'    => 'required|cpf_cnpj',
            'document02'    => 'nullable|string',
            'phone'         => 'nullable|string',
            'email'         => 'nullable|email',
            'type'          => Rule::in(['cliente','fornecedor']),
            'segment'       => 'nullable|integer',
            'obs'           => 'nullable|string',
            'active'        => 'nullable|integer'
        ];
    }

    public function validationData() {
        $data = $this->all();
        $data['document01'] = preg_replace('/[^0-9]/', '', $data['document01']);
        $data['document02'] = preg_replace('/[^0-9]/', '', $data['document02']);
        $data['phone']      = preg_replace('/[^0-9]/', '', $data['phone']);

        return $data;
    }

    public function messages() {
        return [
            'name.required'         => 'O Campo Razão Social ou Nome é obrigatório',
            'name.unique'           => 'O Razão Social ou Nome já esta cadastrado',
            'document01.required'   => 'O Campo CNPJ/CPF é obrigatório',
            'document01.unique'     => 'CNPJ/CPF Já Cadastrado',
            'email'                 => 'O e-mail não parece válido'
        ];
    }
}
