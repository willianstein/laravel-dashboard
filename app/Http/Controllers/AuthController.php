<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Libraries\Response;
use App\Http\Requests\AdmLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    use Helper;

    public function index() {
        return view('auth');
    }

    public function login(AdmLoginRequest $request) {

        $request = $request->validated();

        if(Auth::attempt(['email'=>$request['email'],'password'=>$request['password']])){
            (new Response())->success('Olá, seja bem vindo')->flash();
            echo (new Response())->action('redirect',route(self::goTo()))->json();
        } else {
            echo (new Response())->error('Ops! Confira seu usuário e senha.')->json();
        }

    }

    public function logout() {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('authController.index');
    }

    private static function goTo(): string
    {
        if( Helper::isAdmin())
            return 'adm.dashboard.index';

        return 'app.dashboard.index';

    }

}
