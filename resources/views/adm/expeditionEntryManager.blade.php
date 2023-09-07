@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Expedição: Pedido de {{($orderEntry->type == 'entrada' ? "Entrada" : "Reversa")}} Nº {{$orderEntry->id}}</h1>
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
                                                    <input type="text" name="" id="" placeholder="Unidade" value="{{$orderEntry->office->name}}" disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="" id="" placeholder="Parceiro" value="{{$orderEntry->partner->name}}" disabled data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="status" id="status" placeholder="Status" disabled value="{{$orderEntry->status}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="date" name="forecast" id="forecast" disabled value="{{$orderEntry->forecast}}" placeholder="Data Prevista" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-5">
                                                    <a href="{{route('adm.expeditionEntry.sendToCheck',['orderEntry'=>$orderEntry->id])}}" class="btn btn-success float-right ajax-link ml-3">
                                                        <i class="fas fa-check"></i>
                                                        <span class="text-bold">Enviar P/ Conferência</span>
                                                    </a>
                                                    <a href="{{route('adm.expeditionEntry.received',['orderEntry'=>$orderEntry->id])}}" class="btn btn-primary float-right ajax-link">
                                                        <i class="fas fa-download"></i>
                                                        <span class="text-bold">Receber Pedido</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- TRANSPORT DATA -->
                                    <div class="tab-pane fade" id="transport-data" role="tabpanel">
                                        <div class="row">
                                            <div class="col-10">
                                                <form method="POST" id="transport-form">
                                                    <div class="form-row justify-content-end">

                                                        <div class="form-group col-md-4">
                                                            <?= form_select('modality',($dropModality??null),($orderEntry->transport->modality??null),['id'=>'modality','placeholder'=>'Modalidade','data-toggle'=>'tooltip','class'=>'form-control']); ?>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="carrier_name" id="carrier_name" placeholder="Transportadora" value="{{($orderEntry->transport->carrier_name??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <?= form_select('packaging',($dropPackaging??null),($orderEntry->transport->packaging??null),['id'=>'packaging','placeholder'=>'Acondicionamento','data-toggle'=>'tooltip','class'=>'form-control']); ?>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="driver" id="driver" placeholder="motorista" value="{{($orderEntry->transport->driver??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="driver_document" id="driver_document" placeholder="CPF" value="{{($orderEntry->transport->driver_document??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="car_model" id="car_model" placeholder="Modelo do Carro" value="{{($orderEntry->transport->car_model??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="car_type" id="car_type" placeholder="Tipo do Carro" value="{{($orderEntry->transport->car_type??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" name="car_plate" id="car_plate" placeholder="Placa do Carro" value="{{($orderEntry->transport->car_plate??null)}}" data-toggle="tooltip" class="form-control">
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            @csrf
                                                            <button class="btn btn-block btn-outline-info">Salvar Transporte</button>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="visible-print text-center">
                                                    {!! QrCode::size(150)->generate('google.com.br'); !!}
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
    <div class="modal fade" id="modal-refuse">
        <div class="modal-dialog">
            <div class="modal-content modal-danger">
                <div class="modal-header">
                    <h4 class="modal-title">Recusar Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="refuse-form">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <textarea name="notes" placeholder="Ponderações" rows="8" data-toggle="tooltip" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <button class="btn btn-block btn-outline-danger float-right">
                                    <small><i class="fas fa-ban"></i></small>
                                    <span class="text-bold">RECUSAR</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal-receive">
        <div class="modal-dialog">
            <div class="modal-content modal-success">
                <div class="modal-header">
                    <h4 class="modal-title">Receber Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="receive-form">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <textarea name="notes" placeholder="Ponderações" rows="8" data-toggle="tooltip" class="form-control"></textarea>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <button class="btn btn-block btn-outline-success float-right">
                                    <small><i class="fas fa-check"></i></small>
                                    <span class="text-bold">RECEBER</span>
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
            ajaxSelect2('filter_partner_id', '{{route('adm.stock.getPartners')}}', 'Parceiro');
            ajaxSelect2('partner_id', '{{route('adm.stock.getPartners')}}', 'Parceiro');
            ajaxSelect2('product_id', '{{route('adm.orderItem.findProduct')}}', 'Produto');
            $("#country select").val("{{($orderEntry->recipient->country??'Brasil')}}").change();

            loadDataTable("{{route('adm.expeditionEntry.getListOrderItems',['orderEntry'=>$orderEntry->id])}}", 'item-table');

            $('body').on('click', '.btn-rc', function () {
                let url = "{{route('adm.expeditionEntry.receiveItem')}}";
                    url = url.replace(/[//"]([^//]*)$/,''+$(this).data('id')+'/$1');
                $('#receive-form').attr('action',url);
            });

            $('body').on('click', '.btn-rf', function () {
                let url = "{{route('adm.expeditionEntry.refuseItem')}}";
                    url = url.replace(/[//"]([^//]*)$/,''+$(this).data('id')+'/$1');
                $('#refuse-form').attr('action',url);
            });
        });
    </script>
@endsection
