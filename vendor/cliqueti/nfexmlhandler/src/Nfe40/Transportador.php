<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;

class Transportador extends \CliqueTI\NfeXmlHandler\Contracts\Transportador {

    public function __construct(SimpleXMLElement $transp) {
        $this->modFrete     = strval($transp->modFrete);
        $this->cnpj         = strval($transp->transporta->CNPJ);
        $this->nome         = strval($transp->transporta->xNome);
        $this->ie           = strval($transp->transporta->IE);
        $this->endereco     = strval($transp->transporta->xEnder);
        $this->municipio    = strval($transp->transporta->xMun);
        $this->uf           = strval($transp->transporta->UF);
        $this->qtdVolume    = strval($transp->vol->qVol);
        $this->especie      = strval($transp->vol->esp);
        $this->pesoLiquido  = strval($transp->vol->pesoL);
        $this->pesoBruto    = strval($transp->vol->pesoB);
    }

}