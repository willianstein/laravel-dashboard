<?php

namespace App\Http\Requests;

use App\Models\Transports;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ApiOrderEntryRequest extends FormRequest {
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
            'office_id'         => 'required|integer',
            'transport'         => 'sometimes|nullable|array',
            'transport.modality'        => 'sometimes|nullable|'.Rule::in(array_keys(Transports::MODALITY)),
            'transport.carrier_name'    => 'sometimes|nullable|string',
            'transport.packaging'       => 'sometimes|nullable|'.Rule::in(array_keys(Transports::PACKAGING)),
            'transport.driver'          => 'sometimes|nullable|string',
            'transport.driver_document' => 'sometimes|nullable|string',
            'transport.car_model'       => 'sometimes|nullable|string',
            'transport.car_type'        => 'sometimes|nullable|string',
            'transport.car_plate'       => 'sometimes|nullable|string',
            'forecast'          => 'sometimes|nullable|date',
            'third_system'      => 'required|string',
            'third_system_id'   => 'required|string',
            'items'             => 'required|array'
        ];
    }

    public function messages() {
        return [
            'transport.modality'        => "Modalidade do Frete Inconsistente, opções aceitas: ".implode(', ',array_keys(Transports::MODALITY)),
            'transport.packaging'       => "Acondiconamento do Frete, opções aceitas: ".implode(', ',array_keys(Transports::PACKAGING)),
            'third_system.required'     => "Informe o nome do seu ERP",
            'third_system_id.required'  => "Informe o número do pedido do seu ERP",
            'items'                     => "Itens Ausentes, ou formato incorreto"
        ];
    }
}
