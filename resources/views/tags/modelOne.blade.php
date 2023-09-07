@extends('layouts.printables')

@section('head')
    <style>
        .tag-context h2 {
            box-shadow: 15px 0 0 0 #000, -5px 0 0 0 #000;
            background: #000;
            display: inline;
            color: #fff;
        }
        .tag-box {
            border-top: 3px solid #000;
        }

        h1 {font-size: 5em;}
        h2 {font-size: 4em; font-weight: bolder;}
        p  {font-size: 3em;}
        .img-fluid {width: 100%; margin: 0; padding: 0;}
        hr {border-top: 3px solid #000}
    </style>
@endsection

@section('content')
    @if(!empty($volumes = $orderExit->volumes->sum('quantity')))
        @for($i = 1; $i <= $volumes; $i++)
            <div class="tag-content">
                <div class="row p-3">
                    <div class="col-3 m-0 p-0">
                        <img src="{{asset('img/logo-bb.jpg')}}" class="img-fluid">
                    </div>
                    <div class="col-6 pt-3">
                        <h1 class="text-center">Volume(s):<br>{{$i}}/{{$volumes}}</h1>
                    </div>
                    <div class="col-3">
                        <div class="float-right">{!! QrCode::size(250)->generate($orderUrl) !!}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 px-3 pb-2">
                        <hr>
                        <h1 class="text-center">Pedido: {{str_pad($orderExit->id , 6 , '0' , STR_PAD_LEFT)}}</h1>
                        <h1 class="text-center">Código: {{str_pad($orderExit->third_system_id , 6 , '0' , STR_PAD_LEFT)}}</h1>
                    </div>
                </div>
                <div class="row px-3 tag-context">
                    <div class="col-12">
                        <div class="tag-box">
                            <h2>&nbsp;DESTINATÁRIO&nbsp;</h2>
                            <p>
                                {{$orderExit->recipient->name}} <br>
                                {{$orderExit->recipient->address}}, {{$orderExit->recipient->number}} {{$orderExit->recipient->complement}} <br>
                                {{$orderExit->recipient->neighborhood}} - {{$orderExit->recipient->city}} <br>
                                {{$orderExit->recipient->state}} - CEP: {{$orderExit->recipient->postal_code}}
                            </p>
                        </div>
                    </div>
                    <div class="col-12 pt-5">
                        <div class="tag-box">
                            <h2>&nbsp;OBSERVAÇÕES&nbsp;</h2>
                            <p>
                                {{ ($orderExit->observations ?? null) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endfor
    @endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            window.print();
        });
    </script>
@endsection
