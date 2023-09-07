<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmOrderAddPackageRequest extends FormRequest {
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
            'package_id'    => "required|integer",
            'quantity'      => "required|integer",
            'origin'        => "required|string",
        ];
    }

    public function messages() {
        return [
            'package_id.required' => 'Preencha o pacote ',
            'quantity.required' => 'Preencha a quantidade',
            'origin.required' => 'Preencha a origem'
        ];
    }
}
