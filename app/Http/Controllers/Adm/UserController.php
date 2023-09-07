<?php

namespace App\Http\Controllers\Adm;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmUserRequest;
use App\Models\Partners;
use App\Models\Services;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware(
            'permission:usuarios|cadastrar-usuario|inativar-usuario|editar-usuario|deletar-usuario|ver-usuario',
            ['only' => ['index', 'save', 'getUsers', 'create']]
        );
    }

    /**
     * PÁGINA PRINCIPAL
     */
    public function index()
    {
        $users = User::all();
        return view('adm.user.user', compact(['users']));
    }

    /**
     * SALVA USUÁRIO
     * @param AdmUserRequest $request
     * @return void
     */
    public function save(AdmUserRequest $request)
    {
        $role = Role::findById($request->role_id)->name;
        $listPost = $request->validated();
        $listPost['password'] = Hash::make($listPost['password']);

        if ($role == "PARCEIRO") {
            $partner = Partners::create($listPost);
            $listPost['partner_id'] = $partner->id;
        }

        $user = User::create($listPost);

        if (!$user) {
            echo (new Response())->error('Erro ao Salvar o Registro')->json();
            return;
        }

        $user->assignRole($request->input('role_id'));

        if ($role != "PARCEIRO") {
            echo (new Response())->success('Registro Salvo com Sucesso')->action('redirect', route('adm.user.index'))->flash();
        } else {
            echo (new Response())->success('Registro Salvo com Sucesso')->action('redirect', route('adm.user.create', ['partner' => $partner->id]))->flash();
        }
    }

    /**
     * RETORNA UM USUARIO PELO ID EM JSON
     * @param User $user
     * @return void
     */
    public function getUser(User $user)
    {

        $roles    = Role::get(['id', 'name'])->toArray();
        $teams    = Team::get(['id', 'name'])->toArray();
        $userRole = $user->roles->pluck('name','name')->all();
        $partner  = Partners::where('id', $user->partner_id)->first();


        return view('adm.user.editUser', compact(['roles', 'teams', 'user', 'userRole', 'partner']));
        // echo (new Response())->action('loadForm','myForm')->data($user->toArray())->json();
    }

    /**
     * RETORNA LISTA DE USUARIOS EM JSON
     * @return void
     */

    public function create(?Partners $partner)
    {
        $dropServices = Services::get(['id', 'description'])->toArray();
        $roles = Role::get(['id', 'name'])->toArray();
        $teams = Team::get(['id', 'name'])->toArray();

        return view('adm.user.createUser', compact(['roles', 'teams', 'partner', 'dropServices']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            // 'password' => 'same:confirm-password',
            'role_id' => 'required',

        ]);


        $input = $request->all();
        $user = User::find($id);
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input['password'] = $user->password;
        }


        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('role_id'));

        echo (new Response())->success('Registro Salvo com Sucesso')->action('redirect', route('adm.user.index'))->flash();
    }
}
