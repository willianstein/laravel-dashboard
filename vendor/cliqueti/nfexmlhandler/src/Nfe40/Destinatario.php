<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;

class Destinatario extends \CliqueTI\NfeXmlHandler\Contracts\Destinatario {

    public function __construct(SimpleXMLElement $destinatario) {
        $this->cnpj         = strval($destinatario->CNPJ);
        $this->cpf          = strval($destinatario->CPF);
        $this->ie           = strval($destinatario->IE);
        $this->razao        = strval($destinatario->xNome);
        $this->email        = strval($destinatario->email);
        $this->fone         = strval($destinatario->enderDest->fone);
        $this->logradouro   = strval($destinatario->enderDest->xLgr);
        $this->numero       = strval($destinatario->enderDest->nro);
        $this->complemento  = strval($destinatario->enderDest->xCpl);
        $this->bairro       = strval($destinatario->enderDest->xBairro);
        $this->municipio    = strval($destinatario->enderDest->xMun);
        $this->uf           = strval($destinatario->enderDest->UF);
        $this->cep          = strval($destinatario->enderDest->CEP);
        $this->pais         = strval($destinatario->enderDest->xPais);
    }

}