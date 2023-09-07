<?php

namespace App\Http\Adapters\Vipp;

use App\Models\OrderExits;
use App\Models\TagTransportProviders;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class VipTag {

    /* Ativa Ambiente de Homologação (Boolean) */
    protected static $sandBox = false;

    protected static $orderExit;

    public static function run(?OrderExits $orderExit=null) {
        return new self($orderExit);
    }

    public function __construct(OrderExits $orderExit) {
        self::$orderExit = $orderExit;
    }

    /**
     * CRIA UMA ETIQUETA
     * @return Collection
     */
    public function generate(): Collection {

        /** @var TagTransportProviders $credentials */
        $credentials = TagTransportProviders::where('partner_id',self::$orderExit->partner_id)->first();

        /* Cria a etiqueta */
        return $this->make($credentials);

    }

    /**
     * IMPRIMI UMA ETIQUETA NO VIPP
     * @param int $saida
                0 - Etiqueta ECT (ZVP)
                1 - Etiqueta ECT 4xFolha (PDF)
                2 - Etiqueta ECT 6xFolha (PDF)
                3 - Etiqueta ViPP (ZVP)
                4 - Etiqueta ViPP 4xFolha (PDF)
                5 - Etiqueta ViPP 6xFolha (PDF)
                6 - AR Comum (ZVP)
                7 - AR Digital (ZVP)
                8 - AR Digital Compacto (ZVP)
                9 - AR Digital Integrado (ZVP)
                10 - AR Comum Compacto (ZVP)
                11 - ARComum Integrado(ZVP)
                12 - Reservado para uso interno (se for informado, imprimirá a opção 11)
                13 - Reservado para uso interno (se for informado, imprimirá a opção 11)
                14 – Mini Danfe Integrado (ZVP)
                15 - Mini Danfe Integrada 4x Folha(ZVP)
                16 - Mini Danfe Integrada 6x Folha(ZVP)
                17 – Somente Mini Danfe (PDF)
                18 - Somente Mini Danfe 4xfolha(PDF)
                19 - Somente Mini Danfe 6x Folha(PDF)
                20 – Etiqueta Correios 10x15 (PDF)
                21 – Etiqueta Vipp 10x15 (PDF)
                22 – Declaração de Conteúdo Integrada 10x15 (ZVP)
                23 – Declaração de Conteúdo Integrada 4x Folha (ZVP)
                24 - Declaração de conteúdo Somente 10x15 (PDF)
                25 – Declaração de conteúdo Somente 4xfolha (PDF)
                26 - AR Tradicional Em PDF
                27 - Impressão da Lista Pré-Postagem Em PDF
                28 - Impressão da Lista Pré-Postagem PLP Em PDF

     * @param int $ordem
                0 – Observação 1
                1 - Registro ECT
                2 - Etiqueta ViPP
                3 - Nota Fiscal
                4 - Numero da PLP
                5 - CEP do Destinatário
                6 - Cidade do Destinatário
                7 - UF do Destinatário
                8 - Nome do Destinatário

     * @param $filtro
                0 – Observação 1
                1 - Registro ECT
                2 - Etiqueta ViPP
                3 - Nota Fiscal
                4 - Número da PLP
                5 – Reservado para uso interno(não utilizar)
                6 – Observação 2

     * @return \Illuminate\Http\RedirectResponse
     */
    public function print(int $saida = 20, int $ordem = 8, $filtro = 1) {

        /** @var TagTransportProviders $credentials */
        $credentials = TagTransportProviders::where('partner_id',self::$orderExit->partner_id)->first();

        return redirect('https://vipp.visualset.com.br/vipp/remoto/ImpressaoRemota.php?'.http_build_query([
                'Usr'       => (self::$sandBox ? "onbiws" : $credentials->user),
                'Pwd'       => (self::$sandBox ? "112233" : $credentials->password),
                'Filtro'    => $filtro,
                'Ordem'     => $ordem,
                'Saída'     => $saida,
                'Lista'     => implode(',',[self::$orderExit->transportTag->tag_code])
            ]));

    }

    /**
     * FAZ O POST PARA CRIAÇÃO DA ETIQUETA
     * @param TagTransportProviders $credentials
     * @return Collection|null
     */
    private function make(TagTransportProviders $credentials) {

        $tag = Http::post($credentials->url, [
            'PerfilVipp' => [
                'Usuario'   => (self::$sandBox ? "onbiws" : $credentials->user),
                'Token'     => (self::$sandBox ? "112233" : $credentials->password),
                'IdPerfil'  => (self::$sandBox ? "9363"   : $credentials->metaData()->idPerfil)
            ],
            'ContratoEct' => [
                'NrContrato'            => $credentials->metaData()->codigoContrato,
                'CodigoAdministrativo'  => $credentials->metaData()->codigoAdministrativo,
                'NrCartao'              => $credentials->metaData()->cartao,
            ],
            'Destinatario' => [
                'CnpjCpf'       => self::$orderExit->recipient->document01,
                'Nome'          => self::$orderExit->recipient->name,
                'Endereco'      => self::$orderExit->recipient->address,
                'Numero'        => self::$orderExit->recipient->number,
                'Complemento'   => self::$orderExit->recipient->complement,
                'Bairro'        => self::$orderExit->recipient->neighborhood,
                'Cidade'        => self::$orderExit->recipient->city,
                'UF'            => self::$orderExit->recipient->state,
                'Cep'           => self::$orderExit->recipient->postal_code,
            ],
            'Servico' => [
                'ServicoECT' => '4162' //SEDEX
            ],
            'NotasFiscais' => [[
                'NrNotaFiscal' => self::$orderExit->invoice()->identificacao()->numeroNf
            ]],
            'Volumes' => [[
                'Peso'              => 0,
                'Altura'            => 2,
                'Largura'           => 14,
                'Comprimento'       => 21,
                'ObservacaoVisual'  => self::$orderExit->invoice()->identificacao()->numeroNf
            ]]
        ]);

        if($tag->failed()){
            return null;
        } else {
            return $tag->collect();
        }
    }

}
