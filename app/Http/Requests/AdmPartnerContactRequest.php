<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmPartnerContactRequest extends FormRequest {
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
            'contact_id'    => 'nullable|integer',
            'partner_id'    => 'nullable|integer',
            'name'          => 'required|string',
            'cellphone'     => 'nullable',
            'email'         => 'nullable|email',
            'position'      => 'nullable|string',
            'active'        => 'nullable|integer'
        ];
    }

    public function validationData() {
        $data = $this->all();
        $data['cellphone'] = preg_replace('/[^0-9]/', '', $data['cellphone']);

        return $data;
    }

    public function messages() {
        return [
            'partner_id.required' => 'Especifique o Parceiro',
            'name.required' => 'Por favor, preencha um nome',
            'email.required' => 'Ops... o E-mail não parece válido',
        ];
    }
}
