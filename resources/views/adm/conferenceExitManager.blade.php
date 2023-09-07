@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Conferência: Pedido de Saida Nº {{$orderExit->id}}</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- ORDER HANDLE -->
                    <div class="col-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#main-data" role="tab">O Pedido</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#volumes-data" role="tab">Volumes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#service-data" role="tab">Serviços</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <!-- MAIN DATA -->
                                    <div class="tab-pane fade show active" id="main-data" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="info-box">
                                                    <div class="info-box-content flex-row flex-wrap">
                                                        <h5 class="w-100">Dados do Pedido</h5>
                                                        <p class="w-50 m-0"><b>CD: </b>{{$orderExit->office->name}}</p>
                                                        <p class="w-50 m-0"><b>Parceiro: </b>{{$orderExit->partner->name}}</p>
                                                        <p class="w-50 m-0"><b>Status: </b>{{$orderExit->status}}</p>
                                                        <p class="w-50 m-0"><b>Data de Saida: </b>{{date_fmt($orderExit->forecast,'d/m/Y')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="info-box bg-gradient-dark">
                                                    <div class="info-box-content d-block">
                                                        <h5> Conferência </h5>
                                                        <form id="conferenceItem" action="{{route('adm.conferenceExit.checkItem',compact('orderExit'))}}" method="post">
                                                            @csrf
                                                            <div class="form-row">
                                                                <div class="form-group col-md-5">
                                                                    <input type="number" name="isbn" id="isbn" placeholder="ISBN" data-toggle="tooltip" class="form-control">
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <button type="button" class="btn btn-block btn-info" data-toggle="modal" data-target="#modal-conference">Lote</button>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <a href="{{route('adm.conferenceExit.checked',$orderExit)}}" class="btn btn-block btn-success ajax-link">Concluir</a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mt-3">
                                                <div class="info-box">
                                                    <div class="info-box-content d-block">
                                                        <table id="item-table" class="table table-striped dataTable dtr-inline">
                                                            <thead>
                                                            <tr>
                                                                <th>ISBN</th>
                                                                <th>Titulo</th>
                                                                <th>Editora</th>
                                                                <th>Quantidade</th>
                                                                <th>Conferido</th>
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
                                        </div>
                                    </div>

                                    <!-- VOLUMES DATA -->
                                    <div class="tab-pane fade" id="volumes-data" role="tabpanel">

                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5>Novo Volume</h5>
                                                <div class="info-box bg-gradient-light">
                                                    <div class="info-box-content">
                                                        <form method="post" id="contactForm" action="{{route('adm.conferenceExit.addPackage',compact('orderExit'))}}" class="mt-3">
                                                            <div class="form-row">

                                                                <div class="form-group col-md-2">
                                                                    <input type="number" name="quantity" id="quantity" placeholder="Quantidade" value="1" data-toggle="tooltip" class="form-control">
                                                                </div>

                                                                <div class="form-group col-md-4">
                                                                    <?=form_select('package_id',($dropPackages??null),null,['id'=>'package_id','class'=>'custom-select'])?>
                                                                </div>

                                                                <div class="form-group col-md-3">
                                                                    <?=form_select('origin',($dropPackageOrigins??null),null,['id'=>'origin','class'=>'custom-select'])?>
                                                                </div>

                                                                <div class="form-group col-md-3">
                                                                    @csrf
                                                                    <button class="btn btn-block btn-outline-info">Adicionar Volume</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h5>Imprimir Etiqueta</h5>
                                                <div class="info-box bg-gradient-light">
                                                    <div class="info-box-content">
                                                        <form method="post" id="tagForm" action="{{route('adm.conferenceExit.printTag',compact('orderExit'))}}" class="mt-3 ajax-off" target="_blank">
                                                            <div class="form-row">

                                                                <div class="form-group col-md-7">
                                                                    <?=form_select('tag',($dropTagTemplates??null),null,['id'=>'tag','placeholder'=>'Selecione o Modelo','data-toggle'=>'tooltip','class'=>'custom-select'])?>
                                                                </div>

                                                                <div class="form-group col-md-5">
                                                                    @csrf
                                                                    <button class="btn btn-block btn-outline-info">Imprimir Etiqueta</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mt-3">Volumes Adicionados</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="package-table" class="table table-striped dataTable dtr-inline">
                                                    <thead>
                                                    <tr>
                                                        <th>Quantidade</th>
                                                        <th>Nome</th>
                                                        <th>Origem</th>
                                                        <th>Ações</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SERVICES DATA -->
                                    <div class="tab-pane fade" id="service-data" role="tabpanel">
                                        <form method="post" id="serviceForm" action="{{route('adm.conferenceExit.addService',$orderExit)}}" class="mt-2">
                                            <div class="form-row">

                                                <div class="form-group col-md-3">
                                                    <input type="number" name="quantity" id="quantity" placeholder="Quantidade" data-toggle="tooltip" class="form-control">
                                                </div>

                                                <div class="form-group col-md-7">
                                                    <?=form_select('service_id',($dropServices??null),null,['id'=>'service_id','placeholder'=>'Serviço','data-toggle'=>'tooltip','class'=>'select2'],'Selecione o Serviço')?>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    @csrf
                                                    <input type="hidden" name="partner_service_id" id="partner_service_id">
                                                    <button class="btn btn-block btn-outline-info">Salvar Dados</button>
                                                </div>

                                            </div>
                                        </form>
                                        <hr>
                                        <h5>Servicos Cadastrados</h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <table id="service-table" class="table table-striped dataTable dtr-inline">
                                                    <thead>
                                                    <tr>
                                                        <th>Quantidade</th>
                                                        <th>Serviço</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modal-conference">
        <div class="modal-dialog">
            <div class="modal-content modal-info">
                <div class="modal-header">
                    <h4 class="modal-title">Conferir Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="myForm"  action="{{route('adm.conferenceExit.conferenceInLots',$orderExit)}}">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <input type="text" name="isbn" id="isbn" placeholder="ISBN" data-toggle="tooltip" class="form-control" max="13">
                            </div>
                            <div class="form-group col-12">
                                <input type="text" name="quantity" id="quantity" placeholder="Quantidade" data-toggle="tooltip" class="form-control">
                            </div>
                            <div class="form-group col-12">
                                <?=form_select('supervisor_id',($dropSupervisors??null),null,['id'=>'supervisor_id','class'=>'custom-select'])?>
                            </div>
                            <div class="form-group col-12">
                                <input type="password" name="password" id="password" placeholder="Senha Supervisor" data-toggle="tooltip" class="form-control">
                            </div>

                            <div class="form-group col-12">
                                @csrf &nbsp;
                                <button class="btn btn-block btn-outline-info">
                                    <small><i class="fas fa-check"></i></small>
                                    <span class="text-bold">CONFERIR</span>
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
    <script>
        $(document).ready(function(){
            $("#isbn").focus();

            loadDataTable("{{route('adm.conferenceExit.getListOrderItems',['orderExit'=>$orderExit->id])}}", 'item-table');
            loadDataTable("{{route('adm.conferenceExit.getPackages',['orderExit'=>$orderExit->id])}}", 'package-table');
            loadDataTable("{{route('adm.conferenceExit.getServices',['orderExit'=>$orderExit->id])}}", 'service-table');

            $('#conferenceItem #isbn').keydown(function (event) {
                var charCode = (event.which) ? event.which : event.keyCode;
                if ((charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105) || charCode == 13) {
                    if($(this).val().length === 13 || charCode == 13){
                        $('#conferenceItem').submit();
                    }
                }
            });

        });
    </script>
@endsection
