<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmBudgetRequest extends FormRequest
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
            'id'                => 'nullable|integer',
            'start'             => 'nullable|date',
            'end'               => 'nullable|date',
            'pdf'               => 'nullable|mimes:pdf,xlx,csv|max:2048',
            'objective'         => 'required|string',
            'partner_id'        => 'required|integer',
            'date_conclusion'   => 'required|date',
            'value'             => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
        ];
    }

    public function messages() {
        return [
            'pdf'                      => 'PDF Obrigatório',
            'partner_id.required'      => 'Parceiro Obrigatório',
            'date_conclusion.required' => 'Data de Conclusão Obrigatório',
            'objective.required'       => 'Objetivo Obrigatório',
            'value.required'           => 'Valor Obrigatório',
        ];
    }
}








