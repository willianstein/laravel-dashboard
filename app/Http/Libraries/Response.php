<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Session;

/**
 *
 */
class Response {

    /** @var array */
    private array $data;

    /**  @var array */
    private array $message;

    /** @var array */
    private array $action;

    /**
     * CRIA UMA MENSAGEM DE SUCESSO QUE SERÁ INTERPRETADA PELO FRONT
     * @param string $message
     * @param int|null $duration
     * @return $this
     */
    public function success(string $message, int $duration = null):Response {
        $this->message[] = [
            'type' => 'success',
            'text' => $message,
            'duration' => ($duration??2000)
        ];

        return $this;
    }

    /**
     * CRIA UMA MENSAGEM DE SUCESSO QUE SERÁ INTERPRETADA PELO FRONT
     * @param string $message
     * @param int|null $duration
     * @return $this
     */
    public function error(string $message, int $duration = null):Response {
        $this->message[] = [
            'type' => 'error',
            'text' => $message,
            'duration' => ($duration??2000)
        ];

        return $this;
    }

    /**
     * CRIA UMA MENSAGEM DE SUCESSO QUE SERÁ INTERPRETADA PELO FRONT
     * @param string $message
     * @param int|null $duration
     * @return $this
     */
    public function warning(string $message, int $duration = null):Response {
        $this->message[] = [
            'type' => 'warning',
            'text' => $message,
            'duration' => ($duration??2000)
        ];

        return $this;
    }

    /**
     * CRIA UMA MENSAGEM DE SUCESSO QUE SERÁ INTERPRETADA PELO FRONT
     * @param string $message
     * @param int|null $duration
     * @return $this
     */
    public function info(string $message, int $duration = null):Response {
        $this->message[] = [
            'type' => 'info',
            'text' => $message,
            'duration' => ($duration??2000)
        ];

        return $this;
    }

    /**
     * DETERMINA AÇÕES QUE O AJAX PRECISA FAZER
     * @param string $action
     * @param $value
     * @return $this
     */
    public function action(string $action, $value):Response {
        $this->action[$action] = $value;
        return $this;
    }

    /**
     * DETERMINA OS DADOS QUE SERÃO RETORNADOS
     * @param array $data
     * @return $this
     */
    public function data(array $data): Response {
        $this->data = $data;
        return $this;
    }

    /**
     * CONVERTE O RESPONSE EM JSON
     * @return string
     */
    public function json():string {
        /* Verifica se Existem Mensagens a Exibir */
        if(!empty($this->message)){
            $response['message'] = $this->message;
        }
        /* Verifica se Existem Ações a Executar */
        if(!empty($this->action)){
            $response['action'] = $this->action;
        }
        /* Verifica se Existem Dados a Manipular */
        if(!empty($this->data)){
            $response['data'] = $this->data;
        }
        /* Saida Formato Json */
        return json_encode($response??null);
    }

    /**
     * CRIA UM FLASH RESPONSE
     * @return string|null
     */
    public function flash():? string {
        if(!empty($this->message)){
            Session::put('message', json_encode(['message'=>$this->message]));
            $this->message = [];
        }
        return $this->json();
    }

    /**
     * RECUPERA UM FLASH RESPONSE
     * @return string|null
     */
    public function getFlash():? string {
        if(Session::has('message')){
            $return =   Session::get('message',null);
                        Session::forget('message');
        }
        return $return??null;
    }

}
