<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmPackageRequest extends FormRequest {
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
            'id'        => 'nullable|integer',
            'name'      => "required|string|unique:addressings,name,{$this->id}",
            'weight'    => 'required|integer',
            'active'    => 'integer'
        ];
    }

    public function messages() {
        return [
            'name.required'         => 'Preencha o nome',
            'name.unique'           => 'Pacote já cadastrado'
        ];
    }
}
