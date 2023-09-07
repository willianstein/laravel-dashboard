<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmConferenceServiceRequest extends FormRequest {
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
            'id'            => 'nullable|integer',
            'service_id'    => 'required|integer',
            'quantity'      => 'required|integer'
        ];
    }

    public function messages() {
        return [
            'service_id.required'   => 'Preencha o Serviço',
            'quantity.required'     => 'Preencha a Quantidade'
        ];
    }
}
