<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmOrderItemRequest extends FormRequest
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
            'id'            => 'nullable|integer',
            'quantity'      => 'required|integer',
            'product_id'    => 'required|integer'
        ];
    }

    public function messages() {
        return [
            'quantity.required'     => 'O campo quantidade é obrigatório',
            'product_id.required'   => 'O campo produto é obrigatório'
        ];
    }
}
