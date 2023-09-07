<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;

class Protocolo extends \CliqueTI\NfeXmlHandler\Contracts\Protocolo {

    public function __construct(SimpleXMLElement $protNFe) {
        $this->versao               = strval($protNFe->attributes()->versao);
        $this->ambiente             = strval($protNFe->infProt->tpAmb);
        $this->versaoAplicativo     = strval($protNFe->infProt->verAplic);
        $this->chaveNFe             = strval($protNFe->infProt->chNFe);
        $this->dataProcessamento    = strval($protNFe->infProt->dhRecbto);
        $this->numeroProtocolo      = strval($protNFe->infProt->nProt);
        $this->digestValue          = strval($protNFe->infProt->digVal);
        $this->status               = strval($protNFe->infProt->cStat);
        $this->motivo               = strval($protNFe->infProt->xMotivo);
        $this->assinatura           = strval($protNFe->infProt->Signature);
    }

}