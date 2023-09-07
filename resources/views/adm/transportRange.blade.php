@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Range de Ceps</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!--FORM-->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Manutenção do Cadastro</h5>
                            </div>
                            <div class="card-body">
                                <form method="post" id="myForm">
                                    <div class="form-row">

                                        <div class="form-group col-md-4">
                                            <input type="text" name="name" id="name" placeholder="Nome do Range" data-toggle="tooltip" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <input type="text" name="range_from" id="range_from" placeholder="Cep Inicial" data-toggle="tooltip" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <input type="text" name="range_up_to" id="range_up_to" placeholder="Cep Final" data-toggle="tooltip" class="form-control">
                                        </div>

                                        <div class="form-group col-md-2">
                                            @csrf
                                            <input type="hidden" name="id" id="id">
                                            <button class="btn btn-block btn-outline-info float-right">Salvar Dados</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Últimos Serviços Cadastrados</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Cep Inicial</th>
                                        <th>Cep Final</th>
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
            loadDataTable("{{route('adm.transportRange.getTransportRanges')}}", 'table');
        });
    </script>
@endsection

