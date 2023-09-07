<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;

class Emitente extends \CliqueTI\NfeXmlHandler\Contracts\Emitente {

    public function __construct(SimpleXMLElement $emitente) {
        $this->cnpj         = strval($emitente->CNPJ);
        $this->cpf          = strval($emitente->CPF);
        $this->ie           = strval($emitente->IE);
        $this->razao        = strval($emitente->xNome);
        $this->fantasia     = strval($emitente->xFant);
        $this->fone         = strval($emitente->enderEmit->fone);
        $this->logradouro   = strval($emitente->enderEmit->xLgr);
        $this->numero       = strval($emitente->enderEmit->nro);
        $this->complemento  = strval($emitente->enderEmit->xCpl);
        $this->bairro       = strval($emitente->enderEmit->xBairro);
        $this->municipio    = strval($emitente->enderEmit->xMun);
        $this->uf           = strval($emitente->enderEmit->UF);
        $this->cep          = strval($emitente->enderEmit->CEP);
        $this->pais         = strval($emitente->enderEmit->xPais);
    }

}