<?php

namespace CliqueTI\NfeXmlHandler\Contracts;

abstract class Identificacao {

    /** Código da UF do emitente do Documento Fiscal */
    public string $ufEmitente;

    /** Código Numérico que compõe a Chave de Acesso */
    public string $codigoNf;

    /** Descrição da Natureza da Operação */
    public string $natOperacao;

    /** Indicador da Forma de Pagamento (0 - À Vista, 1 - À Prazo, 3 - Outros) */
    public string $formaPgto;

    /** Modelo Documento Fiscal */
    public string $modalidade;

    /** Serie do documento fiscal */
    public string $serie;

    /** Número do Documento Fiscal */
    public string $numeroNf;

    /** Data de emissão do Documento Fisca */
    public string $dataEmissao;

    /** Tipo Operação */
    public string $tipoNf;

    /** Identificação Destino */
    public string $idDestino;

    /** Código do Municipio */
    public string $codigoMunicipio;

    /** Formato de Impressão (1 - Retrato, 2 - Paisagem) */
    public string $formatoImpressao;

    /** Tipo Emissão */
    public string $tipoEmissao;

    /** Digito Verificador */
    public string $digitoVerificador;

    /** Tipo Ambiente (1 - Produção, 2 - Homologação) */
    public string $ambiente;

    /** Finalidade da Emissão (1 - Normal, 2 - Complementar, 3 - Ajuste) */
    public string $finalidade;

    /** @var string $indFinal */
    public string $indFinal;

    /** @var string $indPres */
    public string $indPres;

    /** @var string $indIntermed */
    public string $indIntermed;

    /** Processo de Emissão */
    public string $processoEmissao;

    /** Versão do Processo */
    public string $versaoProcesso;

}
