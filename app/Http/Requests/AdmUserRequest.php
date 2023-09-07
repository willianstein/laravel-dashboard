<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmUserRequest extends FormRequest {
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
            'id'        => 'nullable|integer',
            'role_id'   => 'required|integer',
            'team_id'   => 'required|integer',
            'name'      => 'required|string',
            'email'     => "required|email|unique:users,email,{$this->id}",
            'password'  => 'required|string',
            'active'    => 'integer'
        ];
    }

    public function messages() {
        return [
            'role_id.required'      => 'Preencha o regra do usuário',
            'team_id.required'      => 'Preencha o regra do time',
            'name.required'         => 'Preencha o nome',
            'email.required'        => 'Preencha o E-mail',
            'email.unique'          => 'E-mail já cadastrado',
            'password.required'     => 'Preencha a Senha'
        ];
    }
}
