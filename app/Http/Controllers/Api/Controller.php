<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

use \App\Http\Controllers\Controller as MainController;

use App\Models\Partners;

/**
 *  MAIN CONTROLER API
 */
class Controller extends MainController {

    /**
     * @var Partners|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected static ?Partners $partner;

    /**
     *
     */
    public function __construct() {
        self::$partner = auth('sanctum')->user();
    }

    /**
     * PADRONIZA RETORNO DE SUCESSO API
     * @param string|null $message
     * @param null $data
     * @param int $code
     * @return Application|ResponseFactory|Response
     */
    public static function success(?string $message, $data = null, $code = 200) {
        return self::response('success',$message,$code,$data);
    }

    /**
     * PADRONIZA RETORNO DO INFORMATIVO PADRÃO
     * @param string|null $message
     * @param $data
     * @param $code
     * @return Application|ResponseFactory|Response
     */
    public static function info(?string $message, $data = null, $code = 200) {
        return self::response('info',$message,$code,$data);
    }

    /**
     * PADRONIZA RETORNO DE ERRO API
     * @param string|null $message
     * @param int $code
     * @param null $errors
     * @param null $data
     * @return Application|ResponseFactory|Response
     */
    public static function error(?string $message, $code = 400, $errors = null, $data = null) {
        return self::response('error',$message,$code,$data,$errors);
    }

    /**
     * PADRONIZA RETORNO API
     * @param string $type
     * @param string|null $message
     * @param int $code
     * @param null $data
     * @param null $errors
     * @return Application|ResponseFactory|Response
     */
    public static function response(string $type, ?string $message, int $code = 200, $data = null, $errors = null) {

        $response['type']                   = $type;
        if($message) { $response['message'] = $message; }
        if($data) { $response['data']       = $data; }
        if($errors){ $response['errors']    = $errors; }

        return response($response,$code);
    }

}
