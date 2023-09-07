<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;

class Identificacao extends \CliqueTI\NfeXmlHandler\Contracts\Identificacao {

    public function __construct(SimpleXMLElement $ide) {
        $this->ufEmitente           = strval($ide->cUF);
        $this->codigoNf             = strval($ide->cNF);
        $this->natOperacao          = strval($ide->natOp);
        $this->formaPgto            = strval($ide->indPag);
        $this->modalidade           = strval($ide->mod);
        $this->serie                = strval($ide->serie);
        $this->numeroNf             = strval($ide->nNF);
        $this->dataEmissao          = strval($ide->dhEmi);
        $this->tipoNf               = strval($ide->tpNF);
        $this->idDestino            = strval($ide->idDest);
        $this->codigoMunicipio      = strval($ide->cMunFG);
        $this->formatoImpressao     = strval($ide->tpImp);
        $this->tipoEmissao          = strval($ide->tpEmis);
        $this->digitoVerificador    = strval($ide->cDV);
        $this->ambiente             = strval($ide->tpAmb);
        $this->finalidade           = strval($ide->finNFe);
        $this->indFinal             = strval($ide->indFinal);
        $this->indPres              = strval($ide->indPres);
        $this->indIntermed          = strval($ide->indIntermed);
        $this->processoEmissao      = strval($ide->procEmi);
        $this->versaoProcesso       = strval($ide->verProc);
    }

}