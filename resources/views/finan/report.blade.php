@extends('layouts.adm')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Relatórios</h1>
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
                            <div class="card-body">
                                <form method="post" id="formReport" action="{{ route('report.save') }}" class="ajax-off">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <select name="table" class="custom-select" data-placement="top" title="Tipo"
                                                placeholder="Tipo" data-toggle="tooltip">
                                                <option value="bills_to_pay">Contas a Pagar</option>
                                                <option value="bills_to_receive">Contas a Receber</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="status" class="custom-select" data-placement="top" title="status"
                                                placeholder="Status" data-toggle="tooltip">
                                                <option value="null">Sem filtro</option>
                                                <option value="aberto">Aberta</option>
                                                <option value="baixada">Baixada</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="input-group">
                                                <input type="date" name="dateInit" id="dateEnd"
                                                    placeholder="Data Inicial" data-toggle="tooltip" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <div class="input-group">
                                                <input type="date" name="dateEnd" id="dateEnd" placeholder="Data Final"
                                                    data-toggle="tooltip" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="id_cost_center" id="filter_cost_center" class="custom-select"
                                                placeholder="Centro de custo" data-toggle="tooltip">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="id_bank" id="filter_bank" class="custom-select"
                                                placeholder="Banco" data-toggle="tooltip">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <select name="partner_id" id="partner_id" class="custom-select"
                                                placeholder="Parceiro" data-toggle="tooltip">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            @csrf
                                            <button class="btn btn-block btn-outline-info float-right" type="submit"
                                                name="action" value="view">Ver Relatório</button>
                                        </div>
                                        <div class="form-group col-md-2">
                                            @csrf
                                            <button class="btn btn-block btn-outline-info float-right" type="submit"
                                                name="action" value="download">Baixar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if ($reports ?? null)
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Registros Cadastrados</h5>
                                </div>
                                <div class="card-body">

                                    <table id="table" class="table dataTable table-striped dtr-inline">
                                        <thead>
                                            <tr>
                                                <th>Descrição</th>
                                                <th>Valor</th>
                                                <th>Competência</th>
                                                <th>Centro Custo</th>
                                                <th>Banco</th>
                                                <th>Saldo Banco</th>
                                                <th>Favorecido</th>
                                                <th>status</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reports as $report)
                                                <tr>
                                                    <th> {{ $report->descricao }}</th>
                                                    <th> {{ $report->valor }}</th>
                                                    <th> {{ $report->data_de_competencia }}</th>
                                                    <th> {{ $report->centro_de_custo }}</th>
                                                    <th> {{ $report->nome_banco }}</th>
                                                    <th> {{ $report->saldo_banco }}</th>
                                                    <th> {{ $report->nome_favorecido }}</th>
                                                    <th> {{ $report->status }}</th>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
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
            ajaxSelect2('partner_id', '{{ route('adm.stock.getPartners') }}', 'Parceiro');
            ajaxSelect2('filter_cost_center', '{{ route('financial.findCostCenter') }}', 'Centro de custo');
            ajaxSelect2('filter_bank', '{{ route('financial.findBank') }}', 'Banco');
        });
    </script>
    <script></script>
@endsection
