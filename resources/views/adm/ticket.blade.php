@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Ticket</h1>
                    </div><!-- /.col -->
                    @can('cadastrar-ticket')
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-sm btn-info float-right" data-toggle="modal"
                                data-target="#newTicket">
                                Novo Ticket
                            </button>
                        </div>
                    @endcan
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        @can('ver-ticket')
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- ITEMS -->
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Últimos Tickets Cadastrados</h5>
                                </div>
                                <div class="card-body">
                                    <table id="table" class="table table-striped dataTable dtr-inline">
                                        <thead>
                                            <tr>
                                                <th>N⁰</th>
                                                <th>Categoria</th>
                                                <th>Solicitado Por</th>
                                                <th>Parceiro</th>
                                                <th>Responsável</th>
                                                <th>Criado em:</th>
                                                <th>Atendido em:</th>
                                                <th>Finalizado em:</th>
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
        @endcan
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('modal')
    <div class="modal fade" id="newTicket">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo Ticket</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="myForm" action="{{ route('adm.ticket.save') }}">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <?= form_select('category_id', $dropCategories ?? null, null, ['id' => 'category_id', 'placeholder' => 'Selecione a Categoria', 'data-toggle' => 'tooltip', 'class' => 'custom-select'], 'Selecione a Categoria') ?>
                            </div>
                            <div class="form-group col-md-6">
                                <?= form_select('partner_id', $dropPartners ?? null, null, ['id' => 'partner_id', 'placeholder' => 'Selecione o Parceiro', 'data-toggle' => 'tooltip', 'class' => 'custom-select'], 'Selecione o Parceiro') ?>
                            </div>
                            <div class="form-group col-md-12">
                                <textarea name="message" id="message" class="form-control" rows="3" placeholder="Como podemos te ajudar?"></textarea>
                            </div>
                        </div>
                        <div class="form-row justify-content-end">
                            <div class="form-group col-md-2">
                                @csrf
                                <button class="btn btn-block btn-outline-primary float-right">Criar Ticket</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            loadDataTable("{{ route('adm.ticket.getTickets') }}", 'table');
        });
    </script>
@endsection
