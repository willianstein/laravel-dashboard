@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Expedição: Pedido de Saída Nº {{$orderExit->id}}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- ORDER ATTRIBUTES -->
                    <div class="col-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#main-data" role="tab">O Pedido</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#transport-data" role="tab">Transporte</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#tags-data" role="tab">Etiquetas</a>
                                    </li>
                                    <li class="nav-item">
                                        <!---<a class="nav-link" data-toggle="pill" href="#service-data" role="tab">Serviços</a>-->
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <!-- MAIN DATA -->
                                    <div class="tab-pane fade show active" id="main-data" role="tabpanel">
                                        <form method="POST" id="myForm">
                                            <div class="form-row justify-content-end">

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="" id="" placeholder="Unidade" value="{{$orderExit->office->name}}" disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="" id="" placeholder="Parceiro" value="{{$orderExit->partner->name}}" disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="status" id="status" placeholder="Status" disabled value="{{$orderExit->status}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="date" name="forecast" id="forecast" disabled value="{{$orderExit->forecast}}" placeholder="Data Prevista" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-2 float-right">
                                                    <a href="{{route('adm.expeditionExit.boardingAll',$orderExit)}}" class="btn btn-block btn-warning" data-toggle="modal" data-target="#modal-order">
                                                        <i class="fas fa-check"></i>
                                                        <span class="text-bold">Concluir todo o pedido</span>
                                                    </a>
                                                </div>

                                                <div class="form-group col-md-2 float-right">
                                                    <a href="{{route('adm.expeditionExit.complete',$orderExit)}}" class="btn btn-block btn-success ajax-link">
                                                        <i class="fas fa-check"></i>
                                                        <span class="text-bold">Concluir o Pedido</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- TRANSPORT DATA -->
                                    <div class="tab-pane fade" id="transport-data" role="tabpanel">
                                        <div class="row">
                                            <div class="col">
                                                <form method="POST" action="{{route('adm.expeditionExit.updateTransport',$orderExit)}}">
                                                    <div class="form-row justify-content-end">

                                                        <div class="form-group col-md-4">
                                                            <?= form_select('modality',($dropModality??null),($orderExit->transport->modality??null),['id'=>'modality','placeholder'=>'Modalidade','data-toggle'=>'tooltip','class'=>'form-control']); ?>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="carrier_name" id="carrier_name" placeholder="Transportadora" value="{{($orderExit->transport->carrier_name??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <?= form_select('packaging',($dropPackaging??null),($orderExit->transport->packaging??null),['id'=>'packaging','placeholder'=>'Acondicionamento','data-toggle'=>'tooltip','class'=>'form-control']); ?>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="driver" id="driver" placeholder="motorista" value="{{($orderExit->transport->driver??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="driver_document" id="driver_document" placeholder="CPF" value="{{($orderExit->transport->driver_document??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="car_model" id="car_model" placeholder="Modelo do Carro" value="{{($orderExit->transport->car_model??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="car_type" id="car_type" placeholder="Tipo do Carro" value="{{($orderExit->transport->car_type??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="car_plate" id="car_plate" placeholder="Placa do Carro" value="{{($orderExit->transport->car_plate??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            @csrf
                                                            <input type="hidden" name="id" id="id" value="{{$orderExit->transport->id}}">
                                                            <button class="btn btn-block btn-outline-info">Salvar Transporte</button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TAGS DATA -->
                                    <div class="tab-pane fade" id="tags-data" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex">
                                                    <a href="{{route('adm.expeditionExit.printSimpleDanfe',$orderExit)}}" target="_blank" class="btn btn-app">
                                                        <i class="fas fa-receipt"></i> Danfe Etiq.
                                                    </a>
                                                    <a href="{{route('adm.expeditionExit.printDanfe',$orderExit)}}" target="_blank" class="btn btn-app">
                                                        <i class="fas fa-file-invoice-dollar"></i> Danfe
                                                    </a>
                                                    <a href="{{route('adm.expeditionExit.printDeclaration',$orderExit)}}" target="_blank" class="btn btn-app">
                                                        <i class="far fa-file-alt"></i> Declaração
                                                    </a>
                                                    @if(!empty($orderExit->invoice) && !empty($third_system_name = $orderExit->hasIntegration->third_system_name))

                                                        @if($third_system_name == "Versa" && strtolower(trim($orderExit->transport->carrier_name??"")) == "correios"  && empty($orderExit->transportTag))
                                                            <a href="{{route('adm.transportTag.generate',$orderExit)}}" class="btn btn-app text-center">
                                                                <img src="{{asset('img/selo-correios-gray.png')}}" class="img-fluid" style="height: 1.2rem;"><br> Gerar Sedex
                                                            </a>
                                                        @endif

                                                        @if(strtolower(trim($orderExit->transport->carrier_name??"")) == "correios" && ($third_system_name == "Horus" || !empty($orderExit->transportTag)))
                                                            <div>
                                                                <a href="{{route('adm.transportTag.print',$orderExit)}}" target="_blank" class="btn btn-app">
                                                                    <i class="fas fa-truck-loading"></i>Transporte
                                                                </a>
                                                                @if($orderExit->transportTag)
                                                                    <p class="text-xs text-center ml-2" id="tagCode" style="cursor: pointer;">{{$orderExit->transportTag->tag_code}}</p>
                                                                @endif
                                                            </div>
                                                        @endif

                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SERVICES DATA -->
                                    <div class="tab-pane fade" id="service-data" role="tabpanel">
                                        Service Data
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="card-title"><h5 class="m-0">Itens deste pedido</h5></div>
                                <div class="card-tools"></div>
                            </div>

                            <div class="card-body">
                                <table id="item-table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="modal-dispatch">
        <div class="modal-dialog">
            <div class="modal-content modal-success">
                <div class="modal-header">
                    <h4 class="modal-title">Concluir Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="dispatch-form">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <textarea name="notes" placeholder="Ponderações" rows="8" data-toggle="tooltip" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <button class="btn btn-block btn-outline-success float-right">
                                    <small><i class="fas fa-check"></i></small>
                                    <span class="text-bold">CONLUIR</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-order">
        <div class="modal-dialog">
            <div class="modal-content modal-success">
                <div class="modal-header">
                    <h4 class="modal-title">Concluir Pedido inteiro?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST"action="{{route('adm.expeditionExit.boardingAll',$orderExit)}}">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <textarea name="notes" placeholder="Ponderações" rows="8" data-toggle="tooltip" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                {{-- <a href="{{route('adm.expeditionExit.boardingAll',$orderExit)}}" class="btn btn-block btn-warning" data-toggle="modal" data-target="#modal-order">
                                    <i class="fas fa-check"></i>
                                    <span class="text-bold">Concluir todo o pedido</span>
                                </a> --}}
                                <button class="btn btn-block btn-outline-success float-right">
                                    <small><i class="fas fa-check"></i></small>
                                    <span class="text-bold">CONCLUIR TODO O PEDIDO</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/buscacep.js')}}"></script>
    <script>
        $(document).ready(function () {

            loadDataTable("{{route('adm.expeditionExit.getListOrderItems',['orderExit'=>$orderExit->id])}}", 'item-table');

            $('body').on('click', '.btn-dispatch', function () {
                let url = "{{route('adm.expeditionExit.completeItem')}}";
                url = url.replace(/[//"]([^//]*)$/,''+$(this).data('id')+'/$1');
                $('#dispatch-form').attr('action',url);
            });

            $('#tagCode').click(function () {
                navigator.clipboard.writeText($(this).html());
                msgToast([{'duration':2000,'type':'success','text':'Código Copiado com Sucesso'}]);
            });
        });
    </script>
@endsection
