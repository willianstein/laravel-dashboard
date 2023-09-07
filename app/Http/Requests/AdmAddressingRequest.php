<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmAddressingRequest extends FormRequest
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
            'id'        => 'nullable|integer',
            'office_id' => 'required|integer',
            'name'      => "required|string|unique:addressings,name,{$this->id}",
            'distance'  => 'required|integer',
            'active'    => 'integer'
        ];
    }

    public function messages() {
        return [
            'office_id.required'    => 'Preencha a Unidade',
            'name.required'         => 'Preencha o Nome',
            'name.unique'           => 'Endereçamento já cadastrado',
            'distance.required'     => 'Preencha o Endereço'
        ];
    }
}
