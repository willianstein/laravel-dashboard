<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmTransportRangeRequest extends FormRequest {
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
            'name'          => "required|string|unique:transport_ranges,name,{$this->id}",
            'range_from'    => 'required|string',
            'range_up_to'   => 'required|string'
        ];
    }

    protected function prepareForValidation() {
        $this->merge([
            'range_from' => preg_replace('/[^0-9]/', '', $this->range_from),
            'range_up_to' => preg_replace('/[^0-9]/', '', $this->range_up_to),
        ]);
    }

    public function messages() {
        return [
            'name.required'         => 'Preencha o nome',
            'name.unique'           => 'Range Já Cadastrado',
            'range_from.required'   => 'Preencha o Cep Inicial',
            'range_up_to.required'  => 'Preencha o Cep Final'
        ];
    }
}
