<?php

namespace App\Http\Controllers\App;

use App\Http\Libraries\Response;
use App\Models\Bearers;
use App\Models\Partners;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

/**
 *  CLASSE DE MANIPULAÇÃO DO TOKEN
 */
class Token extends Controller {

    /**
     * PAGINA INICIAL
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
        $dropPartners = Partners::get(['id','name'])->toArray();
        return view('app.tokenManager',compact('dropPartners'));
    }

    /**
     * SALVA TOKEN
     * @param Request $request
     * @return void
     */
    public function save(Request $request) {

        $request->validate([
            'partner_id'    => 'required|int',
            'name'          => 'required|string'
        ],[
            'partner_id.required'   => 'Informe o Parceiro',
            'name.required'         => 'Informe um Nome para o Token'
        ]);

        if(!$partner = Partners::find($request->partner_id)){
            echo (new Response())->error('Parceiro Não Encontrado')->json();
            return;
        }

        if(!$token = $partner->createToken($request->name)){
            echo (new Response())->error('Falha ao Criar Token')->json();
            return;
        }

        $bearer = Bearers::create([
            'token_id' => $token->accessToken->id,
            'token' => $token->plainTextToken
        ]);

        echo (new Response())->success('Token Criado com Sucesso')->json();

    }

    /**
     * RETORNA LISTA DE BEARER TOKENS
     * @param Partners $partner
     * @return void
     */
    public function getTokens(Partners $partner) {
        foreach ($partner->tokens as $token){
            $link = route('app.token.deleteToken',['partner'=>$partner,'token_id'=>$token->id]);
            $data[] = [
                $token->name,
                (Bearers::where('token_id',$token->id))->first()->token,
                "<a href=\"{$link}\" class=\"badge badge-danger\"><i class=\"fas fa-trash\"></i> EXCLUIR</a>"
            ];
        }
        echo json_encode(($data??['data'=>[]]));
    }

    /**
     * DELETA TOKEN
     * @param Partners $partner
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteToken(Partners $partner, Request $request) {
        $partner->tokens()->where('id', $request->token_id)->delete();
        (new Response())->success('Token Excluido com Sucesso')->flash();
        return redirect()->route('app.token.index');
    }

}
