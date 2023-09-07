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
            <td colspan="1" rowspan="1"><p>SÉRIE: 1</p></td>
            <td colspan="1" rowspan="2"><p class="text-center">DATA EMISSAO:<br>05/01/2023</p></td>
            <td colspan="1" rowspan="2"><p class="text-center">Nº.: 005729</p></td>
        </tr>
        <tr>
            <td colspan="1" rowspan="1"><p>TIPO NF: 1 - SAÍDA</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>CHAVE DE ACESSO:</p>
                <?php $chave = "35230136131747000126550010000057291007120926";?>
                <p class="text-center">{{$chave}}</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-3">
                    <?php
                        $barcode = new \Picqer\Barcode\BarcodeGeneratorSVG();
                        echo $barcode->getBarcode($chave,$barcode::TYPE_CODE_128_C, 3, 100);
                    ?>
                </p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>PROTOCOLO: 135230022067373</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-2">EMITENTE:<br>ASSOCIAÇÃO CULTURAL</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>CNPJ/CPF: 36.131.747/0001-26<</p></td>
            <td colspan="1" rowspan="1"><p>RG/IE: 589.037.056.116</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>RIO GRANDE DA SERRA / SP - CEP: 09450-000</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-2">DESTINATÁRIO:<br>HALLISON CALDEIRA</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>CPNJ/CPF: 122.008.956-74</p></td>
            <td colspan="1" rowspan="1"><p>RG/IE: 00.000.000-0</p></td>
        </tr>
        <tr>
            <td colspan="1" rowspan="1"><p>ENDEREÇO:</p></td>
            <td colspan="2" rowspan="1"><p>
                    RUA TESTE DE SISTEMA, 1329 - SALA 02 <br>
                    NOME DO BAIRRO - NOME DA CIDADE <br>
                    ESTADO - CEP 00000-000
                </p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p class="text-center p-2">TRANSPORTE:<br>TRANSLOVATO</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>PESO LIQUIDO: 1.700KG</p></td>
            <td colspan="1" rowspan="2"><p>VOLUMES: 01</p></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="1"><p>PEDO BRUTO: 1.700KG</p></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="1"><p>TOTAL DA NFe: R$ 294,70<br><small>OUTRAS INFORMA&Ccedil;&Otilde;ES AQUI NA MESMA LINHA (RODAPE)</small></p></td>
        </tr>
    </table>
@endsection

@section('scripts')
    <script>
    </script>
@endsection
