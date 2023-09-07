<?php

namespace App\Http\Controllers\Adm;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Preference extends Controller {

    /**
     * Pagina Perfil do Usuario
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
        $user = Auth::user();
        return view('adm.preference',compact('user'));
    }

    /**
     * Atualiza a Foto do Usuario
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function updatePhoto(User $user, Request $request) {

        /* Apaga Antetior se Houver */
        if(!empty($user->photo)){
            Storage::disk('public')->delete($user->photo);
        }
        $fileName = "user-{$user->id}.".$request->photo->extension();
        $user->photo = $request->photo->storeAs('photos',$fileName,'public');
        $user->save();

        echo (new Response())->success('Foto atualizada com sucesso')->json();
    }

    /**
     * Altera a Senha do Usuario
     * @param User $user
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePassword(User $user, Request $request) {

        $this->validate($request, [
            'password_old'  => 'required|min:6',
            'password'      => 'required|confirmed|min:6',
        ],[
            'password_old.required' => "Digite sua senha antiga",
            'password.required'     => "Digite sua nova senha",
            'password.confirmed'    => "As Senhas não são iguais",
            'password_old.min'      => "Minimo 6 caracteres",
            'password.min'          => "Minimo 6 caracteres"
        ]);

        if(!Hash::check($request->password_old, $user->password)){
            echo (new Response())->error('Ops! A senha antiga esta incorreta')->json();
            return;
        }

        $user->password = Hash::make($request->password);
        $user->save();

        echo (new Response())->success('Senha atualizada com sucesso')->json();
    }

    /**
     * Atualiza Preferencias do Tema
     * @param User $user
     * @param Request $request
     * @return void
     */
    public function updatePreferences(User $user, Request $request) {

        $cfg = array();
        //Theme
        switch ($request->theme){
            case 'Escuro':
                $cfg['body'][] = "dark-mode";
                $cfg['nav'] = "navbar-dark";
                $cfg['aside'] = "sidebar-dark-primary";
                break;
            case 'Misto':
                $cfg['body'][] = "";
                $cfg['nav'] = "navbar-white";
                $cfg['aside'] = "sidebar-dark-primary";
                break;
            case 'Claro':
                $cfg['body'][] = "";
                $cfg['nav'] = "navbar-white";
                $cfg['aside'] = "sidebar-light-primary";
                break;
        }
        //Fonte
        switch ($request->font){
            case 'Pequena':
                $cfg['body'][] = "text-sm";
                break;
            case 'Normal':
                $cfg['body'][] = "";
                break;
        }
        //Menu
        switch ($request->menu){
            case 'Encolhido':
                $cfg['body'][] = "sidebar-collapse";
                break;
            case 'Expandido':
                $cfg['body'][] = "";
                break;
        }

        /* Encoding */
        $cfg['body'] = trim(implode(' ',$cfg['body']));
        $user->theme_preferences = json_encode($cfg);
        $user->save();

        echo (new Response())->success('Preferências atualizadas com sucesso')->json();

    }

}
