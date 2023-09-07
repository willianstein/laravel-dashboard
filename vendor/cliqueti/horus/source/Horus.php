<?php

namespace CliqueTI\Horus;

abstract class Horus {

    /** @var string  */
    private string $apiUrl;

    /** @var string  */
    private string $endPoint;

    /** @var string  */
    private string $method;

    /** @var array  */
    private array $headers;

    /** @var string  */
    private string $fieldsFormat;

    /** @var string|array|null */
    private $fields;

    /** @var string|array|null */
    private $response;

    /** @var string|array|null */
    private $error;

    /** @var array  */
    private array $debug;

    /** @var bool */
    private bool $sslVerifypeer;

    public function __construct(string $apiUrl, string $user, string $password, $sslVerifypeer = true) {
        $this->sslVerifypeer = $sslVerifypeer;
        $this->apiUrl = $apiUrl;
        $this->headers([
            'Authorization' => 'Basic '.base64_encode("{$user}:{$password}"),
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * SET HEADERS
     * @param array|null $headers
     */
    protected function headers(?array $headers):void {
        if(!$headers){return;}
        foreach ($headers as $k => $v) {
            $this->header($k,$v);
        }
    }

    /**
     * SET HEADER
     * @param string $key
     * @param string|null $value
     */
    protected function header(string $key, string $value=null):void {
        $this->headers[] = "{$key}: {$value}";
    }

    /**
     * FIELDS OF REQUEST
     * @param array|null $fields
     * @param string $format
     */
    protected function fields(?array $fields, string $format="json"): void {
        $this->fieldsFormat = $format;

        if($format == "json") {
            $this->fields = (!empty($fields) ? json_encode($fields) : null);
        }
        if($format == "query"){
            $this->fields = (!empty($fields) ? http_build_query($fields) : null);
        }
    }

    /**
     * THE REQUEST
     * @param string $endPoint
     * @param string $method
     * @param string|null $apiUrl
     */
    protected function request(string $endPoint, string $method, string $apiUrl = null):void {
        $this->endPoint = $endPoint;
        $this->method = $method;
        $this->dispatch($apiUrl);
    }

    /**
     * RETURN RESPONSE
     * @return mixed
     */
    public function response() {
        return $this->response;
    }

    /**
     * RETURN ERROR
     * @return mixed
     */
    public function error() {
        return $this->error;
    }

    /**
     * DEBUG REQUEST
     * @return mixed
     */
    public function debug() {
        return $this->debug;
    }


    /**
     * DISPATCH WITH CURL
     */
    private function dispatch():void {
        $queryString = ($this->fieldsFormat == "query" && $this->method == "GET"  ? "?".$this->fields : null);
        $curl = curl_init("{$this->apiUrl}/{$this->endPoint}{$queryString}");
        curl_setopt_array($curl,[
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->fields,
            CURLOPT_HTTPHEADER => ($this->headers),
            CURLOPT_SSL_VERIFYPEER => $this->sslVerifypeer,
            CURLOPT_VERBOSE => true,
            CURLINFO_HEADER_OUT => true,
        ]);
        $this->response = json_decode(curl_exec($curl));
        $this->debug = curl_getinfo($curl);
        curl_close($curl);
        $this->errorHandler();
    }

    /**
     * ERROR HANDLE
     * @return void
     */
    private function errorHandler() {

        if(empty($this->response)){
            $this->error = [
                'Falha' => 'Sem Conteudo',
                'Mensagem' => 'Não foi retornado nenhum conteudo'
            ];
            $this->response = null;
            return;
        }

        if(!empty($this->response[0]->Falha)){
            $this->error = $this->response[0];
            $this->response = null;
            return;
        }

        $this->error = null;
    }

}