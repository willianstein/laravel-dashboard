<?php

namespace CliqueTI\NfeXmlHandler\Contracts;

abstract class Transportador {

    /** Modalidade do frete */
    public string $modFrete;

    /** CNPJ */
    public string $cnpj;

    /** Inscrição Estadual */
    public string $ie;

    /** Razão Social Transportadora */
    public string $nome;

    /** Endereço da Transportadora */
    public string $endereco;

    /** Municipio da Transportadora */
    public string $municipio;

    /** Estado da Transportadora */
    public string $uf;

    /** Quantidade de Volumes */
    public string $qtdVolume;

    /** Espécie dos volumes */
    public string $especie;

    /** Total Peso Liquido Transportado */
    public string $pesoLiquido;

    /** Total Peso Bruto Transportado */
    public string $pesoBruto;

}
