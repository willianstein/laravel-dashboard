<?php

namespace CliqueTI\NfeXmlHandler\Contracts;

abstract class Protocolo {

    /** Versão do XML */
    public string $versao;

    /** Ambiente (1 - Produção, 2 - Homologação) */
    public string $ambiente;

    /** Versão do Aplicativo que processou o Lote. */
    public string $versaoAplicativo;

    /** Chave de Acesso da NF-e */
    public string $chaveNFe;

    /** Data e hora de processamento */
    public string $dataProcessamento;

    /** Número do Protocolo da NF-e */
    public string $numeroProtocolo;

    /** Digest Value da NF-e processada */
    public string $digestValue;

    /** Código do status da resposta para a NF-e */
    public string $status;

    /** Descrição literal do status da resposta para a NF-e. */
    public string $motivo;

    /** Assinatura XML do grupo identificado pelo atributo “Id” */
    public string $assinatura;

}