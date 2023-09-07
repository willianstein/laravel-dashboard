<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdmOrderEntryRequest extends FormRequest {
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
     //TODO AJUSTAR CRIACAO DE USUARIO PARCEIRO
    public function rules() {
        return [
            'id'            => 'nullable|integer',
            'office_id'     => 'required|integer',
            'observations'  => 'nullable|string',
            'partner_id'    =>  Rule::excludeIf(Auth::user()->type == 'user_app'),
            'forecast'      => 'nullable|date_format:Y-m-d'
        ];
    }

    public function messages() {
        return [
            'office_id.required'        => 'O campo unidade é obrigatório',
            'partner_id.required'       => 'O campo parceiro é obrigatório'
        ];
    }
}
