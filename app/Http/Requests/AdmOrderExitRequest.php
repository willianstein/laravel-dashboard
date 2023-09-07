<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmOrderExitRequest extends FormRequest {
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
            'office_id'     => 'required|integer',
            'partner_id'    => 'required|integer',
            'forecast'      => 'nullable|date_format:Y-m-d',
            'observations'  => 'nullable|string',
        ];
    }

    public function messages() {
        return [
            'office_id.required'        => 'O campo unidade é obrigatório',
            'partner_id.required'       => 'O campo parceiro é obrigatório'
        ];
    }
}
