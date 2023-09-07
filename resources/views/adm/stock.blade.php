@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastro de Estoque</h1>
                    </div><!-- /.col -->
                    @can('importar-estoque-csv')
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-sm float-right" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                    Ações em Lote
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('adm.stockImport.sendCsv') }}">Importar Estoque
                                        (CSV)</a>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <!--FORM-->
                    @can('cadastrar-estoque')
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Manutenção do Cadastro</h5>
                                </div>
                                <div class="card-body">
                                    <form method="post" id="myForm">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <select name="office_id" id="office_id" placeholder="Unidade"
                                                    data-toload="addressing_id"
                                                    data-url="{{ route('adm.stock.getAddressing') }}/"
                                                    class="form-control load-select" data-toggle="tooltip">
                                                    <option value="">Selecione</option>
                                                    @if (!empty($offices))
                                                        @foreach ($offices as $office)
                                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <select name="addressing_id" id="addressing_id" class="custom-select select2"
                                                    placeholder="Endereçamento" data-toggle="tooltip">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <select name="partner_id" id="partner_id" class="custom-select"
                                                    placeholder="Parceiro" data-toggle="tooltip">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <?= form_select('type', $dropStockTypes, null, ['id' => 'type', 'class' => 'custom-select', 'placeholder' => 'Tipo', 'data-toggle' => 'tooltip']) ?>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <select name="product_id" id="product_id" class="custom-select"
                                                    placeholder="Produto" data-toggle="tooltip">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <input type="number" name="quantity_min" id="quantity_min"
                                                    placeholder="Quantidade Mínima" data-toggle="tooltip" class="form-control">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <input type="number" name="quantity_max" id="quantity_max"
                                                    placeholder="Quantidade Máxima" data-toggle="tooltip" class="form-control">
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
                    @endcan
                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Registros Cadastrados</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>Unidade</th>
                                            <th>Parceiro</th>
                                            <th>ISBN</th>
                                            <th>Produto</th>
                                            <th>Endereçamento</th>
                                            <th>Tipo</th>
                                            <th>Qtd Máximo</th>
                                            <th>Qtd Mínimo</th>
                                            <th>Qtd Atual</th>
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
            ajaxSelect2('product_id', '{{ route('adm.stock.getProducts') }}', 'Produto');
            loadDataTable("{{ route('adm.stock.getStocks') }}", 'table');
        });
    </script>
@endsection
