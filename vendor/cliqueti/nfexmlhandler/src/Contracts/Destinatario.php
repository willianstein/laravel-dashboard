<?php

namespace CliqueTI\NfeXmlHandler\Contracts;

use SimpleXMLElement;

abstract class Destinatario {

    public $cnpj;
    public $cpf;
    public $ie;
    public $razao;
    public $email;
    public $fone;
    public $logradouro;
    public $numero;
    public $complemento;
    public $bairro;
    public $municipio;
    public $uf;
    public $cep;
    public $pais;

}