<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmFinancialRequest extends FormRequest
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
            'id'          => 'nullable|integer',
            'name'        => "required|string",
            'code'        => "required|unique:cost_center,code,{$this->id}",
            'parent_code' => 'nullable|integer',
            'type'        => 'nullable|string',
            'condition'   => 'nullable|string',
        ];
    }

    public function messages() {
        return [
            'name.required'         => 'Preencha a Nome ou Nome já existente',
            'code.required'         => 'Preencha o Código ou Código já existente',
            'code.unique'           => 'Código já cadastrado',
            'parent_code.unique'    => 'Preencha o Código Pai',

        ];
    }
}
