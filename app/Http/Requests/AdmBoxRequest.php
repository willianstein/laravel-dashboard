<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmBoxRequest extends FormRequest
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
            'id'                     => 'nullable|integer',
            'id_sector'              => 'required|integer',
            'id_bank'                => 'required|integer',
            // 'id_history_moving_box'  => 'required|integer',
            'status'                 => 'nullable|string',
            'responsible'            => 'required|string',
            'value'                  => 'required|string',
            'goal'                   => 'required|string',
            'id_cost_center'         => 'required|integer',
        ];
    }

    public function messages() {
        return [
            'id_sector.required'       => 'Setor Obrigatório',
            'balance.required'         => 'Saldo Obrigatório',
            'id_bank.required'         => 'Informe o Banco de Pagamento',
            'value.required'           => 'Valor Obrigatório',
            'repetition.required'      => 'Tipo de Repetição Obrigatório',
            'id_favored.required'      => 'Favor Informar o favorecido para o Pagamento',
            'value.required'           => 'Valor Obrigatório',
            'goal.required'            => 'Finalidade Obrigatório',
            'id_cost_center.required'  => 'Centro de custo Obrigatório'
        ];
    }
}









