@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Relatórios de Pedidos de Saída</h1>
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
                    <div class="col-12">
                        <form action="{{route('adm.reports.orderExit.setFilter')}}">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <input type="date" name="start_date" placeholder="Data Inicio" data-toggle="tooltip" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="date" name="end_date" placeholder="Data Término" data-toggle="tooltip" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <a href="{{route('adm.reports.orderExit.clearFilter')}}" class="btn btn-block btn-danger ajax-link"> Limpar Filtros </a>
                                </div>
                                <div class="form-group col-md-2">
                                    @csrf
                                    <button class="btn btn-block btn-info"> Filtrar Dados </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Serviços Informados</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>CNPJ / CPF</th>
                                        <th>Razão</th>
                                        <th>ID Pedido</th>
                                        <th>Cod. Pedido</th>
                                        <th>ISBN</th>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
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
<link href="{{asset('vendor/datatables-custom/datatables.min.css')}}" rel="stylesheet">
@endsection

@section('scripts')
<script src="{{asset('vendor/datatables-custom/datatables.min.js')}}"></script>

<script>
    $(document).ready(function () {
        loadDataTableWithButtons("{{route('adm.reports.orderExit.getOrderExits')}}", 'table');
    });
</script>
@endsection
