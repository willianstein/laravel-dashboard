<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmProductRequest extends FormRequest {
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
            'id'        => 'nullable',
            'isbn'      => 'required|max:13',
            'title'     => 'required',
            'publisher' => 'nullable',
            'category'  => 'nullable',
            'synopsis'  => 'nullable',
            'height'    => 'required',
            'width'     => 'required',
            'length'    => 'required',
            'weight'    => 'required',
            'cover'     => 'nullable|file',
            'active'    => 'nullable',
        ];
    }

    public function validationData() {
        $data = $this->all();
        $data['height']     = str_convert_to_double($data['height']);
        $data['width']      = str_convert_to_double($data['width']);
        $data['length']     = str_convert_to_double($data['length']);
        $data['weight']     = str_convert_to_double($data['weight']);
        $data['title']      = mb_convert_case($data['title'], MB_CASE_TITLE, 'UTF-8');
        $data['publisher']  = mb_convert_case($data['publisher'], MB_CASE_TITLE, 'UTF-8');
        $data['category']   = mb_convert_case($data['category'], MB_CASE_TITLE, 'UTF-8');
        return $data;
    }

    public function messages() {
        return [
            'isbn.required'     => 'O campo ISBN é obrigatório',
            'isbn.max'          => 'O campo ISBN não parece válido',
            'title.required'    => 'O campo Título é obrigatório',
            'height.required'   => 'O campo Altura é obrigatório',
            'width.required'    => 'O campo Largura é obrigatório',
            'length.required'   => 'O campo Comprimento é obrigatório',
            'weight.required'   => 'O campo Peso é obrigatório',
        ];
    }
}
