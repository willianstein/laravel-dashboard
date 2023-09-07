<?php

namespace CliqueTI\NfeXmlHandler\Nfe40;

use SimpleXMLElement;
use CliqueTI\NfeXmlHandler\Nfe;
use CliqueTI\NfeXmlHandler\Contracts\Nfe as ContractNfe;

/**
 *  LEITOR XML da NFe V4.00
 */
class LeitorXml extends Nfe implements ContractNfe {

    /** @var SimpleXMLElement */
    private SimpleXMLElement $nfe;

    /**
     * CONSTRUTOR
     * @param SimpleXMLElement $nfe
     */
    public function __construct(SimpleXMLElement $nfe) {
        $this->nfe = $nfe;
    }

    /**
     * @return Identificacao
     */
    public function identificacao():? Identificacao {
        return new Identificacao($this->nfe->NFe->infNFe->ide);
    }

    /**
     * @return Emitente
     */
    public function emitente():? Emitente {
        return new Emitente($this->nfe->NFe->infNFe->emit);
    }

    /**
     * @return Destinatario
     */
    public function destinatario():? Destinatario {
        return new Destinatario($this->nfe->NFe->infNFe->dest);
    }

    /**
     * @return TotalImpostos
     */
    public function totalImpostos():? TotalImpostos {
        return new TotalImpostos($this->nfe->NFe->infNFe->total->ICMSTot);
    }

    /**
     * @return Transportador
     */
    public function transportador():? Transportador {
        return new Transportador($this->nfe->NFe->infNFe->transp);
    }

    /**
     * @return DadosAdicionais
     */
    public function dadosAdicionais():? DadosAdicionais {
        return new DadosAdicionais($this->nfe->NFe->infNFe->infAdic);
    }


    /**
     * @return Protocolo
     */
    public function protocolo():? Protocolo {
        return new Protocolo($this->nfe->protNFe);
    }
}