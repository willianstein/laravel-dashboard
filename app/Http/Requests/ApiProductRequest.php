<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ApiProductRequest extends FormRequest {
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
            'isbn'      => 'required|string|size:13|regex:/[0-9]{13}/|unique:products,isbn',
            'title'     => 'required',
            'publisher' => 'nullable',
            'category'  => 'nullable',
            'synopsis'  => 'nullable',
            'height'    => 'required|numeric',
            'width'     => 'required|numeric',
            'length'    => 'required|numeric',
            'weight'    => 'required|numeric',
            'cover'     => 'nullable|file',
            'active'    => 'nullable',
        ];

    }

    public function messages() {
        return [
            'isbn.required'     => 'O campo ISBN é obrigatório',
            'isbn.max'          => 'O campo ISBN não parece válido',
            'isbn.size'         => 'O campo ISBN deve conter 13 números',
            'isbn.regex'        => 'O campo ISBN deve conter apenas numeros',
            'isbn.unique'       => 'Este ISBN já esta cadastrado',
            'title.required'    => 'O campo Título é obrigatório',
            'height.required'   => 'O campo Altura é obrigatório',
            'height.numeric'    => 'O campo Altura deve conter apenas números',
            'width.required'    => 'O campo Largura é obrigatório',
            'width.numeric'     => 'O campo Largura deve conter apenas números',
            'length.required'   => 'O campo Comprimento é obrigatório',
            'length.numeric'    => 'O campo Comprimento deve conter apenas números',
            'weight.required'   => 'O campo Peso é obrigatório',
            'weight.numeric'    => 'O campo Peso deve conter apenas números',
        ];
    }

    public function failedValidation(Validator $validator) {

        $return = new JsonResponse([
            'type'      => 'error',
            'message'   => $validator->errors()->first()." (existe(m) outro(s) ".($validator->errors()->count() - 1)." erro(s))",
            'errors'    => $validator->errors()
        ], 422);

        throw new ValidationException($validator, $return);

    }

}
