@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastros Produtos</h1>
                    </div><!-- /.col -->
                    @can('importar-produtos-csv')
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-sm float-right" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                    Ações em Lote
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('adm.productImport.sendCsv') }}">Importar Produtos
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
                    @can('cadastro-produtos')
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Manutenção do Cadastro</h5>
                                </div>
                                <div class="card-body">
                                    <form method="post" id="myForm">
                                        <div class="form-row">

                                            <div class="form-group col-md-3">
                                                <div class="input-group">
                                                    <input type="text" name="isbn" id="isbn" placeholder="ISBN"
                                                        data-toggle="tooltip" class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <div class="input-group">
                                                    <input type="text" name="title" id="title" placeholder="Título"
                                                        data-toggle="tooltip" class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="text" name="publisher" id="publisher" placeholder="Editora"
                                                    data-toggle="tooltip" class="form-control">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="text" name="category" id="category" placeholder="Categoria"
                                                    data-toggle="tooltip" class="form-control">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="number" name="height" id="height"
                                                    placeholder="Altura (Em Milímetros)" data-toggle="tooltip"
                                                    class="form-control" step="1">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="number" name="width" id="width"
                                                    placeholder="Largura (Em Milímetros)" data-toggle="tooltip"
                                                    class="form-control" step="1">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="number" name="length" id="length"
                                                    placeholder="Comprimento (Em Milímetros)" data-toggle="tooltip"
                                                    class="form-control" step="1">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <input type="number" name="weight" id="weight"
                                                    placeholder="Peso (Em Gramas)" data-toggle="tooltip" class="form-control"
                                                    step="1">
                                            </div>

                                            <div class="form-group col-md-9">
                                                <textarea name="synopsis" id="synopsis" placeholder="Sinopse" rows="4" data-toggle="tooltip"
                                                    class="form-control"></textarea>
                                            </div>

                                            <div class="form-group col-md-3">
                                                <div class="d-flex flex-wrap justify-content-end">
                                                    <div class="custom-file mb-4">
                                                        <input type="file" name="cover" id="cover"
                                                            class="custom-file-input">
                                                        <label class="custom-file-label" for="inputGroupFile01">Capa</label>
                                                    </div>

                                                    @csrf
                                                    <input type="hidden" name="id" id="id">
                                                    <button class="btn btn-outline-info float-right">Salvar Dados</button>
                                                </div>
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
                                <h5 class="m-0">Últimos Produtos Cadastrados</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ISBN</th>
                                            <th>Titulo</th>
                                            <th>Editora</th>
                                            <th>Categoria</th>
                                            <th>Ativo</th>
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
            loadDataTable("{{ route('adm.product.getProducts') }}", 'table');
        });
    </script>
@endsection
