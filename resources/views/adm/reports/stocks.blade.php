@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Relatórios de Estoque</h1>
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
                        <form action="{{route('adm.reports.stock.setFilter')}}">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <input type="text" name="addressing" placeholder="Endereçamento" data-toggle="tooltip" class="form-control">
                                </div>
                                <div class="form-group col-md-4">
                                    <?=form_select('partner_id',($dropPartners??null),null,['placeholder'=>'Parceiro','data-toggle'=>'tooltip','class'=>'custom-select'],'Selecione um Parceiro');?>
                                </div>
                                <div class="form-group col-md-4">
                                    <select name="product_id" id="product_id" class="custom-select" placeholder="Produto (ISBN ou Título)" data-toggle="tooltip">
                                    </select>
                                </div>
                                <div class="form-group col-md-8"></div>
                                <div class="form-group col-md-2">
                                    <a href="{{route('adm.reports.stock.clearFilter')}}" class="btn btn-block btn-danger ajax-link"> Limpar Filtros </a>
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
                                <h5 class="m-0">Produtos em Estoque Encontrados</h5>
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
        loadDataTableWithButtons("{{route('adm.reports.stock.getStocks')}}", 'table');

        ajaxSelect2('product_id', '{{route('adm.orderItem.findProduct')}}', 'Produto');
    });
</script>
@endsection
