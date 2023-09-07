@extends('layouts.adm')

<style>
    input[type=file]::file-selector-button {
        margin-right: 10px;
        border: none;
        background: #084cdf;
        padding: 5px 10px;
        border-radius: 10px;
        color: #fff;
        cursor: pointer;
        transition: background .2s ease-in-out;
    }

    input[type=file]::file-selector-button:hover {
        background: #0d45a5;
    }
</style>

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Orçamentos</h1>
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
                    @can('cadastrar-orcamentos')
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Cadastro</h5>
                                </div>
                                <div class="card-body">
                                    <form method="post" id="formBudget">
                                        <div class="form-row">

                                            <div class="form-group col-md-2">
                                                <div class="input-group">
                                                    <input type="date" name="start" id="start" placeholder="Data Final"
                                                        data-placement="top" title="Data Inicial" data-toggle="tooltip"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <div class="input-group">
                                                    <input type="date" name="end" id="end" placeholder="Data Final"
                                                        data-placement="top" title="Data Final" data-toggle="tooltip"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <select name="partner_id" id="partner_id" class="custom-select"
                                                    data-placement="top" title="Parceiro" placeholder="Parceiro"
                                                    data-toggle="tooltip">
                                                </select>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <div class="input-group">
                                                    <input type="date" name="date_conclusion" id="date_conclusion"
                                                        data-placement="top" title="Data Conclusão" placeholder="Data Final"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">R$</span>
                                                    </div>
                                                    <input type="text" name="value" id="value" data-placement="top"
                                                        data-placement="top" title="Valor" title="Saldo" placeholder="Valor"
                                                        pattern="^\d*(\.\d{0,2})?$" data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-7">
                                                <textarea name="objective" id="objective" placeholder="Objetivo" data-placement="top" title="Objetivo"
                                                    data-toggle="tooltip" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group mb-3">
                                                <input style="padding-bottom: 50px" type="file" name="pdf"
                                                    data-placement="top" title="Arquivo PDF" class="form-control">
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
                                            <th>Descrição</th>
                                            <th>Início</th>
                                            <th>Fim</th>
                                            <th>Conclusão</th>
                                            <th>Parceiro</th>
                                            <th>Valor</th>
                                            <th>Status</th>
                                            <th>Anexo</th>
                                            <th>Ações</th>
                                            <th>Gerar Ordem</th>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            ajaxSelect2('partner_id', '{{ route('adm.stock.getPartners') }}', 'Parceiro');
            // ajaxSelect2('product_id', '{{ route('adm.stock.getProducts') }}', 'Produto');
            loadDataTable("{{ route('budget.getBudget') }}", 'table');
        });
    </script>
@endsection
