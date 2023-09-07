@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Relatórios de Parceiros</h1>
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
{{--                    <div class="col-md-12">--}}
{{--                        <div class="card card-primary card-outline">--}}
{{--                            <div class="card-header">--}}
{{--                                <h5 class="m-0">Filtros</h5>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Parceiros Encontrados</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                    <tr>
                                        <th>CNPJ / CPF</th>
                                        <th>Razão</th>
                                        <th>Fantasia</th>
                                        <th>Telefone</th>
                                        <th>E-mail</th>
                                        <th>Endereço</th>
                                        <th>Bairro</th>
                                        <th>Cidade</th>
                                        <th>Estado</th>
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
    $(document).ready(function () {
        loadDataTable("{{route('adm.reports.partner.getPartners')}}", 'table');
    });
</script>
@endsection
