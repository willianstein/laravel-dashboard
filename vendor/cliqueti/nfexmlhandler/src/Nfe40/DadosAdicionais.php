<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;

class DadosAdicionais extends \CliqueTI\NfeXmlHandler\Contracts\DadosAdicionais {

    public function __construct(SimpleXMLElement $infAdic) {
        $this->infoComplementares = strval($infAdic->infCpl);
    }

}