<?php

namespace CliqueTI\NfeXmlHandler\Contracts;

use SimpleXMLElement;

interface Nfe {

    public function identificacao():? Identificacao;
    public function emitente():? Emitente;
    public function destinatario():? Destinatario;
    public function totalImpostos():? TotalImpostos;
    public function transportador():? Transportador;
    public function dadosAdicionais():? DadosAdicionais;
    public function protocolo():? Protocolo;

}