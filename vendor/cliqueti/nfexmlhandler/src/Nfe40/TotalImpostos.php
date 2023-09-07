<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;

class TotalImpostos extends \CliqueTI\NfeXmlHandler\Contracts\TotalImpostos {

    public function __construct(SimpleXMLElement $total) {
        $this->vBC          = strval($total->vBC);
        $this->vICMS        = strval($total->vICMS);
        $this->vICMSDeson   = strval($total->vICMSDeson);
        $this->vFCPUFDest   = strval($total->vFCPUFDest);
        $this->vICMSUFDest  = strval($total->vICMSUFDest);
        $this->vICMSUFRemet = strval($total->vICMSUFRemet);
        $this->vFCP         = strval($total->vFCP);
        $this->vBCST        = strval($total->vBCST);
        $this->vST          = strval($total->vST);
        $this->vFCPST       = strval($total->vFCPST);
        $this->vFCPSTRet    = strval($total->vFCPSTRet);
        $this->vProd        = strval($total->vProd);
        $this->vFrete       = strval($total->vFrete);
        $this->vSeg         = strval($total->vSeg);
        $this->vDesc        = strval($total->vDesc);
        $this->vII          = strval($total->vII);
        $this->vIPI         = strval($total->vIPI);
        $this->vIPIDevol    = strval($total->vIPIDevol);
        $this->vPIS         = strval($total->vPIS);
        $this->vCOFINS      = strval($total->vCOFINS);
        $this->vOutro       = strval($total->vOutro);
        $this->vNF          = strval($total->vNF);
    }

}