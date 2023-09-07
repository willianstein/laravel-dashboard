@extends('layouts.printables')

@section('head')
    <style>
        table, th, td {
            border: 3px solid black;
            border-collapse: collapse;
            padding: 0;
            margin: 0;
        }
        table {width: 100%;}
        td  {padding: 0.1rem 0.5rem}
        h1 {font-weight: bold;}
        p {margin: 0; padding: 0; font-size: 2.3em; font-weight: bold; line-height: 1.2em;}
    </style>
@endsection

@section('content')
    <table>
        <tr>
            <td colspan="3" rowspan="1"><h1 class="text-center">DANFE SIMPLICFICADA - ETIQUETA</h1></td>
        </tr>
        <tr>
            <td colspan="1" rowspan="1"><p>SÉRIE: {{$nfe->identificacao()->serie}}</p></td>
            <td colspan="1" rowspan="2"><p class="text-center">DATA EMISSAO:<br>{{date_fmt($nfe->identificacao()->dataEmissao,'d/m/Y H:m')}}</p></td>
            <td colspan="1" rowspan="2"><p class="text-center">Nº.: {{$nfe->identificacao()->numeroNf}}</p></td>
        </tr>
        <tr>
            <?php $typeNF = [0=>'Entrada', 1=>'Saída']; ?>
            <td colspan="1" rowspan="1"><p>TIPO NF: {{$nfe->identificacao()->tipoNf}} - {{$typeNF[$nfe->identificacao()->tipoNf]}}</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>CHAVE DE ACESSO:</p>
                <p class="text-center">{{$nfe->protocolo()->chaveNFe}}</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-3">
                    <?php
                        $barcode = new \Picqer\Barcode\BarcodeGeneratorSVG();
                        echo $barcode->getBarcode($nfe->protocolo()->chaveNFe,$barcode::TYPE_CODE_128_C, 3, 100);
                    ?>
                </p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>PROTOCOLO: {{$nfe->protocolo()->numeroProtocolo}}</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-2">EMITENTE:<br>{{$nfe->emitente()->razao}}</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>CNPJ/CPF: {{$nfe->emitente()->cnpj}}</p></td>
            <td colspan="1" rowspan="1"><p>RG/IE: {{$nfe->emitente()->ie}}</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>{{$nfe->emitente()->municipio}} / {{$nfe->emitente()->uf}} - CEP: {{$nfe->emitente()->cep}}</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-2">DESTINATÁRIO:<br>{{$nfe->destinatario()->razao}}</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>CPNJ/CPF: {{empty($nfe->destinatario()->cnpj)?$nfe->destinatario()->cpf:$nfe->destinatario()->cnpj}}</p></td>
            <td colspan="1" rowspan="1"><p>RG/IE: {{$nfe->destinatario()->ie}}</p></td>
        </tr>
        <tr>
            <td colspan="1" rowspan="1"><p>ENDEREÇO:</p></td>
            <td colspan="2" rowspan="1"><p>
                    {{$nfe->destinatario()->logradouro}}, {{$nfe->destinatario()->numero}} {{$nfe->destinatario()->complemento}} <br>
                    {{$nfe->destinatario()->bairro}} - {{$nfe->destinatario()->municipio}} <br>
                    {{$nfe->destinatario()->uf}} - CEP: {{$nfe->destinatario()->cep}}
                </p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-2">TRANSPORTE:<br>{{$nfe->transportador()->nome}}</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>PESO LIQUIDO: {{$nfe->transportador()->pesoLiquido}}</p></td>
            <td colspan="1" rowspan="2"><p>VOLUMES: {{$nfe->transportador()->qtdVolume}}</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>PEDO BRUTO: {{$nfe->transportador()->pesoBruto}}</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>TOTAL DA NFe: R$ {{$nfe->totalImpostos()->vNF}}<br>
                    <small>{{$nfe->dadosAdicionais()->infoComplementares}}</small></p></td>
        </tr>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            window.print();
        });
    </script>
@endsection
