<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Enums\UserTypeEnum;

class AdmBankRequest extends FormRequest
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

        $userType = 4;
        $this->role_id = 2;
//        if(!$this->id &&  $userType == UserTypeEnum::CLIENT){
//            $this->role_id = 2;
//        }else{
//            $this->role_id = 2;
//        }


        // dd(Rule::unique('bank')->ignore('role_id', $this->role_id));
        //  dd(Rule::unique('bank')->where(fn ($query) => $query->where('role_id', $this->role_id)));
        return [
            'id'          => 'nullable|integer',
            'name'        => 'required|unique:bank|max:255',
            // 'name'        => Rule::unique('bank')->where(fn ($query) => $query->where('role_id', $this->role_id))->ignore('role_id'),
            'balance'     => ['required', 'regex:/[0-9]([0-9]|-(?!-))+/'],
        ];
    }

    public function messages() {
        return [
            'name.required'         => 'Preencha o Nome do Banco',
            'balance.required'      => 'Preencha o saldo',
            'name.unique'           => 'Banco já cadastrado'
        ];
    }
}
