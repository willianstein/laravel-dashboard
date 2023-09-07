<?php

namespace CliqueTI\NfeXmlHandler\Contracts;

abstract class TotalImpostos {

    /** Base de Cálculo do ICMS */
    public string $vBC;

    /** Valor Total do ICMS */
    public string $vICMS;

    /** Valor Desoneração do ICMS */
    public  string $vICMSDeson;

    /** Valor do Fundo de Combate a Pobreza da UF de Destino */
    public string $vFCPUFDest;

    /** Valor do ICMS Interestadual para a UF de Destino */
    public string $vICMSUFDest;

    /** Valor do ICMS Interestadual para UF Remetente */
    public string $vICMSUFRemet;

    /** Valor Fundo de Combate à Pobreza */
    public string $vFCP;

    /** Valor da base de cálculo do ICMS ST */
    public string $vBCST;

    /** Somatório do valor do ICMS com Substituição Tributária */
    public string $vST;

    /** Valor do FCP retido por ST */
    public string $vFCPST;

    /** Valor Total do FCP retido anteriormente por Substituição Tributária difere do somatório dos itens */
    public string $vFCPSTRet;

    /** Valor Total Bruto dos Produtos  */
    public string $vProd;

    /** Valor Total do Frete */
    public string $vFrete;

    /** Valor Total do Seguro */
    public string $vSeg;

    /** Valor Total do Desconto  */
    public string $vDesc;

    /** Valor Total do II */
    public string $vII;

    /** Valor Total do IPI */
    public string $vIPI;

    /** VAlor Total do IPI Devolvido */
    public string $vIPIDevol;

    /** Valor do PIS  */
    public string $vPIS;

    /** Valor do COFINS  */
    public string $vCOFINS;

    /** Outras Despesas acessórias */
    public string $vOutro;

    /** Valor Total da NF-e  */
    public string $vNF;

}