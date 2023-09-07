<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmStockRequest extends FormRequest {
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
            'type'                      => 'required|string',
            'office_id'                 => 'required|integer',
            'partner_id'                => 'required|integer',
            'product_id'                => 'required|integer',
            'addressing_id'             => "required|integer|unique:stocks,addressing_id,{$this->id}",
            'quantity'                  => 'nullable|integer',
            'quantity_min'              => 'nullable|integer',
            'quantity_max'              => 'nullable|integer',
            'third_party_system'        => 'nullable|string',
            'third_party_system_code'   => 'nullable|string',
        ];
    }

    public function messages() {
        return [
            'type.required'             => 'O campo tipo é obrigatório',
            'office_id.required'        => 'O campo unidade é obrigatório',
            'partner_id.required'       => 'O campo parceiro é obrigatório',
            'product_id.required'       => 'O campo produto é obrigatório',
            'addressing_id.required'    => 'O campo endereçamento é obrigatório',
            'addressing_id.unique'      => 'Endereçamento em uso'
        ];
    }
}
