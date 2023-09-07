@extends ( Helper::isAdmin() ? 'layouts.adm' : 'layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Pedido de {{($orderEntry->type == 'entrada' ? "Entrada" : "Reversa")}} Nº {{$orderEntry->id}}</h1>
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
                                        <form method="POST" id="myForm" action="{{route('adm.orderEntry.updateForecast',['orderEntry'=>$orderEntry->id])}}">
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
                                                    <div class="input-group">
                                                        <input type="date" name="forecast" id="forecast" value="{{$orderEntry->forecast}}" placeholder="Data Prevista" data-toggle="tooltip" class="form-control">
                                                        <div class="input-group-append">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success" data-toggle="tooltip" title="Atualizar Previsão"><i class="fas fa-calendar-check"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-8">
                                                    <textarea name="observations" id="observations" placeholder="Observações" data-toggle="tooltip" class="form-control" rows="1" disabled>{{trim(preg_replace('/\s+/',' ',$orderEntry->observations))}}</textarea>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <a href="{{route('adm.orderEntry.receive',['orderEntry'=>$orderEntry])}}" class="btn btn-success float-right ajax-link ml-3">
                                                        <i class="fas fa-check"></i>
                                                        <span class="text-bold">Concluir Pedido</span>
                                                    </a>
                                                    <a href="{{route('adm.orderEntry.cancel',['orderEntry'=>$orderEntry])}}" class="btn btn-outline-danger float-right ajax-link">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="text-bold">Cancelar Pedido</span>
                                                    </a>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <!-- TRANSPORT DATA -->
                                    <div class="tab-pane fade" id="transport-data" role="tabpanel">
                                        <form method="POST" id="transport-form" action="{{route('adm.orderEntry.transport',['orderEntry'=>$orderEntry->id])}}">
                                            <div class="form-row justify-content-end">

                                                <div class="form-group col-md-3">
                                                    <?= form_select('modality',($dropModality??null),($orderEntry->transport->modality??null),['id'=>'modality','placeholder'=>'Modalidade','data-toggle'=>'tooltip','class'=>'form-control']); ?>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="carrier_name" id="carrier_name" placeholder="Transportadora" value="{{($orderEntry->transport->carrier_name??null)}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <?= form_select('packaging',($dropPackaging??null),($orderEntry->transport->packaging??null),['id'=>'packaging','placeholder'=>'Acondicionamento','data-toggle'=>'tooltip','class'=>'form-control']); ?>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="driver" id="driver" placeholder="motorista" value="{{($orderEntry->transport->driver??null)}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="driver_document" id="driver_document" placeholder="CPF" value="{{($orderEntry->transport->driver_document??null)}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="car_model" id="car_model" placeholder="Modelo do Carro" value="{{($orderEntry->transport->car_model??null)}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="car_type" id="car_type" placeholder="Tipo do Carro" value="{{($orderEntry->transport->car_type??null)}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <input type="text" name="car_plate" id="car_plate" placeholder="Placa do Carro" value="{{($orderEntry->transport->car_plate??null)}}" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-2">
                                                    @csrf
                                                    <input type="hidden" name="transport_id" id="transport_id" value="{{($orderEntry->transport->id??null)}}">
                                                    <button class="btn btn-block btn-outline-info">Salvar Transporte</button>
                                                </div>

                                            </div>
                                        </form>
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
                                <div class="card-tools">
                                    <button type="button" class="btn btn-xs btn-success mx-2" data-toggle="modal" data-target="#modal-new-item">
                                        <small><i class="fas fa-plus"></i></small>
                                        <span class="text-bold">Adicionar Item</span>
                                    </button>
                                </div>
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
    <div class="modal fade" id="modal-new-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="myForm" action="{{route('adm.orderEntry.addItem',['orderEntry'=>$orderEntry->id])}}">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input type="number" name="quantity" id="quantity" placeholder="Quantidade" data-toggle="tooltip" class="form-control">
                            </div>

                            <div class="form-group col-12">
                                <select name="product_id" id="product_id" class="custom-select" placeholder="Produto (ISBN ou Título)" data-toggle="tooltip">
                                </select>
                            </div>

                            <div class="form-group col-12">
                                @csrf
                                <button class="btn btn-block btn-outline-info float-right">
                                    <small><i class="fas fa-plus"></i></small>
                                    <span class="text-bold">Adicionar Item</span>
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

            loadDataTable("{{route('adm.orderEntry.getListOrderItems',['orderEntry'=>$orderEntry->id])}}", 'item-table');
        });
    </script>
@endsection
