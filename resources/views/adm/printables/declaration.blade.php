@extends('layouts.printables')

@section('content')
    <div class="container my-4">
        <div class="row">
            <div class="col-4">
                <img class="img-fluid" src="https://cdn.awsli.com.br/188/188355/arquivos/correios-logo-5.png">
            </div>
            <div class="col-8">
                <h2 class="text-right">Declaração de Conteúdo</h2>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-12">
                <div class="card">
                    <table class="table table-sm m-0">
                        <tbody>
                        <tr>
                            <th>Remetente:</th>
                            <td colspan="3">{{$orderExit->partner->name}}</td>
                            @php($address = ($orderExit->partner->address)[0])
                            @php(setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese'))
                        </tr>
                        <tr>
                            <th>Endereço:</th>
                            <td colspan="3">{{$address->address}}, {{$address->number}}</td>
                        </tr>
                        <tr>
                            <th>Cidade/UF:</th>
                            <td>{{$address->city}} / {{$address->state}}</td>
                            <th>CEP:</th>
                            <td>{{$address->postal_code}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-12">
                <div class="card">
                    <table class="table table-sm m-0">
                        <tbody>
                        <tr>
                            <th>Destinatário:</th>
                            <td colspan="3">{{$orderExit->recipient->name}}</td>
                        </tr>
                        <tr>
                            <th>Endereço:</th>
                            <td colspan="3">{{$orderExit->recipient->address}}, {{$orderExit->recipient->number}}</td>
                        </tr>
                        <tr>
                            <th>Cidade/UF:</th>
                            <td>{{$orderExit->recipient->city}} / {{$orderExit->recipient->state}}</td>
                            <th>CEP:</th>
                            <td>{{$orderExit->recipient->postal_code}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row my-2">
            <div class="col-12">
                <table class="table table-sm table-bordered m-0">
                    <thead>
                    <tr class="thead-light">
                        <th colspan="3" class="text-center">Identificação dos Bens</th>
                    </tr>
                    <tr>
                        <th class="text-center">Discriminação do Conteúdo</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-center">Peso</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        $totalProducts = 0;
                        $totalWeight = 0;
                        foreach ($orderExit->items as $orderItem){
                            $totalProducts += $orderItem->quantity;
                            $totalWeight += $orderItem->product->weight;
                        }
                    ?>
                    <tr>
                        <td class="text-center">Livros</td>
                        <td class="text-center">{{$totalProducts}}</td>
                        <td class="text-center">{{number_format(($totalWeight / 1000),2,',','.')}} Kg.</td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="2">Valor Total</td>
                        <td class="text-center">R$ 0,00</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-sm table-bordered m-0">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-center">Declaração</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="p-2">
                            <small>Declaro, não ser pessoa física ou jurídica, que realize, com habitualidade ou em volume que caracterize intuito comercial, operações de circulação de mercadoria, ainda que estas se iniciem no exterior, que o conteúdo declarado e não está sujeito à tributação, e que sou o único responsável por eventuais penalidades ou danos decorrentes de informações inverídicas.</small>
                            <br><br>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-3 p-0">
                                        <p class="m-0 text-center" style="border-bottom: 1px solid #000;float:left;width:95%">{{$address->city}}</p>,
                                    </div>
                                    <div class="col-1 p-0">
                                        <p class="m-0 text-center" style="border-bottom: 1px solid #000;float:left;width:65%">{{strftime('%d')}}</p> de
                                    </div>
                                    <div class="col-2 p-0">
                                        <p class="m-0 text-center" style="border-bottom: 1px solid #000;float:left;width:75%;">{{strftime('%B')}}</p> de
                                    </div>
                                    <div class="col-1 p-0">
                                        <p class="m-0 text-center" style="border-bottom: 1px solid #000;">{{strftime('%Y')}}</p>
                                    </div>
                                    <div class="col-5">
                                        <p class="m-0" style="border-bottom: 1px solid #000;">&nbsp;</p>
                                        <p class="m-0 text-center">Assinatura do<br>Declarante/Remetente</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <table class="table table-sm m-0">
                        <thead>
                        <tr>
                            <td style="border-top:0px;" colspan="2"><strong>Atenção:</strong> O declarante/remetente é responsável exclusivamente pelas informações declaradas.</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2">Observações:</td>
                        </tr>
                        <tr>
                            <td style="border-top:0px;">I.</td>
                            <td style="border-top:0px;"><small>É Contribuinte de ICMS qualquer pessoa física ou jurídica, que realize, com habitualidade ou em volume que caracterize intuito comercial, operações de circulação de mercadoria ou prestações de serviços de transportes interestadual e intermunicipal e de comunicação, ainda que as operações e prestações se iniciem no exterior (Lei Complementar nº 87/96 Art. 4º).</small></td>
                        </tr>
                        <tr>
                            <td style="border-top:0px;">II.</td>
                            <td style="border-top:0px;"><small>Constitui crime contra a ordem tributária suprimir ou reduzir tributo, ou contribuição social e qualquer acessório: quando negar ou deixar de fornecer, quando obrigatório, nota fiscal ou documento equivalente, relativa a venda de mercadoria ou prestação de serviço, efetivamente realizada, ou fornecê-la em desacordo com a legislação. Sob pena de reclusão de 2 (dois) a 5 (anos), e multa (Lei 8.137/90 Art. 1º, V).</small></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
