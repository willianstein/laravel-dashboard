<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmPartnerServiceRequest extends FormRequest {
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
            'partner_service_id' => 'nullable|integer',
            'service_id' => 'nullable|integer',
            'price' => 'required|string',
            'active' => 'integer'
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'price' => money($this->price,'DB'),
        ]);
    }

    public function messages() {
        return [
            'service_id.required' => 'Preencha o Serviço',
            'price.required' => 'Preencha o Preço'
        ];
    }
}
