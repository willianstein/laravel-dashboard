@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Configurações / Acesso ao BBEms</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-2">
                        @include('app.snippets.configMenu')
                    </div>
                    <div class="col-12 col-md-10 p-1">
                        <div class="d-flex justify-content-between flex-wrap mb-3">
                            <h5 class="m-0 p-0">Tokens Cadastrados</h5>
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-default">Gerar Token</button>
                        </div>
                        <div class="d-flex mb-3">
                            <?=form_select('partner_id',($dropPartners??null),null,[
                                'placeholder' => 'Parceiro',
                                'class' => 'form-control select2 getTokens',
                                'data-toggle' => 'tooltip'
                            ],'Selecione o Parceiro');?>
                        </div>
                        <table id="table" class="table table-striped dataTable dtr-inline">
                            <thead>
                            <tr>
                                <th>Nome do Token</th>
                                <th>Token</th>
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
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('modal')

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Gerar Novo Token</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="form-separate" action="{{route('app.token.save')}}">
                        @csrf
                        <div class="form-row">

                            <div class="form-group col-12">
                                <?=form_select('partner_id',($dropPartners??null),null,[
                                    'id' => 'partner_id',
                                    'placeholder' => 'Parceiro',
                                    'class' => 'form-control select2 getTokens',
                                    'data-toggle' => 'tooltip'
                                ],'Selecione o Parceiro');?>
                            </div>

                            <div class="form-group col-12">
                                <input type="text" name="name" id="name" placeholder="Nome do Token" data-toggle="tooltip" class="form-control">
                            </div>

                            <div class="form-group col-12">
                                <button class="btn btn-primary btn-block">Gerar Token</button>
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
        $(document).ready(function () {
            var table = $('#table').DataTable();
            $('.getTokens').change(function (){
                $.getJSON('{{route("app.token.getTokens")}}/'+$(this).val(), function (data) {
                    table.clear();
                    if(data.length){
                        $.each(data, function (key, fields) {
                            table.row.add(fields).draw();
                        });
                    }else {
                        table.draw();
                    }
                });
            });
        });
    </script>
@endsection
