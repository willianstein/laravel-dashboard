@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Importar Estoque</h1>
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
                                <h5 class="m-0">Etapa 1 - Selecione o Arquivo CSV</h5>
                            </div>
                            <div class="card-body">
                                <form method="post" id="myForm" enctype="multipart/form-data" class="ajax-off">
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <input type="file" name="file" id="file" placeholder="Arquivo CSV" data-toggle="tooltip" class="form-control">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <select name="partner_id" id="partner_id" class="custom-select" placeholder="Parceiro" data-toggle="tooltip">
                                            </select>
                                        </div>

                                        <div class="form-group col-md-1">
                                            <select name="delimiter" id="delimiter" placeholder="Separador das colunas" data-toggle="tooltip" class="custom-select">
                                                <option value=","> Virgula ( , ) </option>
                                                <option value=";"> Ponto e Virgula ( ; ) </option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            @csrf
                                            <button class="btn btn-block btn-outline-info float-right">Enviar Arquivo</button>
                                        </div>

                                    </div>
                                </form>
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

@section('scripts')
    <script>
        $(document).ready(function () {
            ajaxSelect2('partner_id', '{{route('adm.stock.getPartners')}}', 'Parceiro');
        });
    </script>
@endsection
