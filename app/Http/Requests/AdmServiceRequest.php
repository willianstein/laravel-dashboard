<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmServiceRequest extends FormRequest {
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
            'description'   => "required|string|unique:services,description,{$this->id}",
            'price'         => 'required|string',
            'active'        => 'integer'
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'price' => money($this->price,'DB'),
        ]);
    }

    public function messages() {
        return [
            'description.required'  => 'Preencha a Descrição',
            'description.unique'    => 'Serviço Já Cadastrado',
            'price.required'        => 'Preencha o Preço'
        ];
    }
}
