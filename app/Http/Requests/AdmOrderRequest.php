<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmOrderRequest extends FormRequest {
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
            'type'                  => 'required|string',
            'partner_id'            => 'required|integer',
            'recipient_id'          => 'required|integer',
            'reference'             => 'nullable|string',
            'reference_id'          => 'nullable|string',
            'invoice'               => 'nullable|string',
            'content_declaration'   => 'nullable|string',
            'channel'               => 'nullable|string',
            'status_id'             => 'nullable|string'
        ];
    }

    public function messages() {
        return [
            'type.required'         => 'O campo tipo é obrigatório',
            'partner_id.required'   => 'O campo parceiro é obrigatório',
            'recipient_id.required' => 'O campo destinatário é obrigatório'
        ];
    }

}
