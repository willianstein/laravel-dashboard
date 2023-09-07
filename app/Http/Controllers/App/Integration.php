<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Response;
use App\Models\Integrations;
use Illuminate\Http\Request;

class Integration extends Controller {

    public function index() {
        return view('app.integrationManager');
    }

    public function save(Request $request) {

        $arrIntegration = [
            'partner_id'    => $request->partner_id,
            'name'          => $request->name,
            'driver'        => $request->driver,
            'url'           => $request->url,
            'user'          => $request->user,
            'password'      => $request->password,
            'token'         => $request->token,
            'filters'       => null];

        /* Orders Filters */
        $arrIntegration['filters']['ordersExit'] = [
            'COD_EMPRESA' => $request->COD_EMPRESA,
            'COD_FILIAL'  => $request->COD_FILIAL,
        ];

        /* Encode Filters */
        $arrIntegration['filters'] = json_encode($arrIntegration['filters']);

        /* Check Exists */
        if($integration = Integrations::where('partner_id',$request->partner_id)->first()){
            $integration->delete();
        }

        /* Create */
        $integration = Integrations::create($arrIntegration);

        (new Response())->success('Integração Realizada com Sucesso')->flash();
        return redirect()->route('app.integration.index');

    }

}
