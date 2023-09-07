<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmBillsRequestReceive extends FormRequest
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
            'description'       => 'required|string',
            'value'             => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
            'id_cost_center'    => 'required|integer',
            'date_received'     => 'required|date',
            'id_bank'           => 'required|integer',
            'repetition'        => 'required|string',
            'id_favored'        => 'required|string',
        ];
    }

    public function messages() {
        return [
            'description.required'     => 'Descrição da Ordem Obrigatória',
            'id_cost_center.required'  => 'Centro de Custo Obrigatório',
            'id_bank.required'         => 'Informe o Banco de Pagamento',
            'value.required'           => 'Valor Obrigatório',
            'repetition.required'      => 'Tipo de Repetição Obrigatório',
            'id_favored.required'      => 'Favor Informar o favorecido para o Pagamento',
            'date_received.required'   => 'Data de Vencimento Obrigatório'
        ];
    }
}









