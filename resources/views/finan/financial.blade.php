@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastro de Centro de Custo</h1>
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
                    @can('cadastrar-centro-de-custo')
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Cadastro</h5>
                                </div>
                                <div class="card-body">
                                    <form method="post" id="formOrdem">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <div class="input-group">
                                                    <input type="text" name="name" id="name" placeholder="Nome"
                                                        data-toggle="tooltip" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <input type="number" name="code" id="code" data-placement="top"
                                                    title="Código" placeholder="Código" data-toggle="tooltip"
                                                    class="form-control">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <input type="number" name="parent_code" id="parent_code" data-placement="top"
                                                    title="Código Pai" placeholder="Código Pai" data-toggle="tooltip"
                                                    class="form-control">
                                            </div>

                                            <div class="form-group col-md-4">
                                                <select name="type" id="type" class="custom-select" data-placement="top"
                                                    title="Tipo" placeholder="Tipo" data-toggle="tooltip">
                                                    <option value="sintetico">Sintético</option>
                                                    <option value="analitico">Analítico</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <select name="condition" id="condition" class="custom-select"
                                                    data-placement="top" title="Condição" placeholder="Condição"
                                                    data-toggle="tooltip">
                                                    <option value="receita">Receita</option>
                                                    <option value="despesa">Despesa</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-3">
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
                                            <th>Nome</th>
                                            <th>Código</th>
                                            <th>Código Pai</th>
                                            <th>tipo</th>
                                            <th>Condição</th>
                                            <th>ativo</th>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            loadDataTable("{{ route('finan.financial.getCostCenter') }}", 'table');
        });
    </script>
@endsection
