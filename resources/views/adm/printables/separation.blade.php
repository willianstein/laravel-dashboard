@extends('layouts.printables')

@section('content')
    <div class="wrapper">

        <section class="invoice">

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <div>
                            <img src="{{asset('img/logo-bb.jpg')}}" width="140">
                        </div>
                        <div class="p-2 d-flex justify-content-between" style="width: 100%;">
                            <div>
                                <p class="text-lg text-bold">
                                    BB Serviços Logistica <br>
                                    Unidade: {{$orderExit->office->name}}
                                </p>
                            </div>
                            <div class="flex-2"><h2 class="m-0 p-0 text-center">Ficha de Separação</h2></div>
                            <div>
                                <p class="text-right text-lg text-bold">
                                    N⁰ Pedido: {{$orderExit->id}} <br>
                                    N⁰ Origem: {{($orderExit->third_system_id??"ND")}}<br>
                                </p>
                                <p class="text-right">
                                    <?php
                                    $barcode = new Picqer\Barcode\BarcodeGeneratorSVG();
                                    $barcode = $barcode->getBarcode(str_pad($orderExit->id , 6 , '0' , STR_PAD_LEFT), $barcode::TYPE_CODE_128, 2, 50);
                                    echo $barcode;
                                    ?>
                                </p>
                            </div>
                        </div>
                        <div>
                            {!! QrCode::size(140)->generate($orderExit->id) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12"><hr></div>
                <div class="col-6">
                    <p class="p-0 m-0">
                        <b>Parceiro:</b> {{$orderExit->partner->name}} <br>
                        <b>Transportadora:</b> {{($orderExit->transport->carrier_name ?? 'Indisponível')}} <br>
                        <b>Criado em:</b> {{date_fmt($orderExit->created_at,'d/m/Y H:m')}} <br>
                        <b>Última Alteração:</b> {{date_fmt($orderExit->updated_at,'d/m/Y H:m')}} <br>
                    </p>
                </div>
                <div class="col-6">
                    <p>
                        <b>Destinatário:</b>    {{$orderExit->recipient->name}}<br>
                        <b>CNPJ / CPF:</b>      {{$orderExit->recipient->document01}}<br>
                        <b>Endereço:</b>        {{$orderExit->recipient->address}},
                                                {{$orderExit->recipient->number}}
                                                {{$orderExit->recipient->complement}} -
                                                {{$orderExit->recipient->neighborhood}} -
                                                {{$orderExit->recipient->city}} /
                                                {{$orderExit->recipient->state}} <br>
                        <b>CEP:</b>             {{$orderExit->recipient->postal_code}}
                    </p>
                </div>
                <div class="col-12"><hr></div>
            </div>

            <div class="row">
                <div class="col-12 border">
                    <p class="p-2 mt-3">
                        <b>Observações: </b>
                        <br>{{$orderExit->observations}}
                    </p>
                </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Titulo</th>
                            <th>ISBN</th>
                            <th>Qtd.</th>
                            <th>Endereçamento</th>
                            <th>Observações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($items))
                            @foreach($items as $item)
                                <tr>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->isbn}}</td>
                                    <td>{{$item->quantity}}</td>
                                    <td>{{$item->addressing}}</td>
                                    <td style="border-bottom: 1px solid"></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

    </div>
@endsection

@section('scripts')
@endsection
