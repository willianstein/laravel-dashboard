@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Importar Produtos</h1>
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
                                <h5 class="m-0">Etapa 2 - Mapeando as Informações</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{route('adm.productImport.processCsv')}}" method="post" id="myForm">
                                    <?php $iF = session('importFields'); ?>
                                    <div class="form-row">

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o ISBN
                                                <?=form_select(
                                                    'isbn',$csvHeaders,($iF['isbn']??null),[
                                                        'id' => 'isbn',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Titulo
                                                <?=form_select(
                                                    'title',$csvHeaders,($iF['title']??null),[
                                                        'id' => 'title',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Editora
                                                <?=form_select(
                                                    'publisher',$csvHeaders,($iF['publisher']??null),[
                                                        'id' => 'publisher',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Categoria
                                                <?=form_select(
                                                    'category',$csvHeaders,($iF['category']??null),[
                                                        'id' => 'category',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Altura (CM)
                                                <?=form_select(
                                                    'height',$csvHeaders,($iF['height']??null),[
                                                        'id' => 'height',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Largura (CM)
                                                <?=form_select(
                                                    'width',$csvHeaders,($iF['width']??null),[
                                                        'id' => 'width',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Comprimento (CM)
                                                <?=form_select(
                                                    'length',$csvHeaders,($iF['length']??null),[
                                                        'id' => 'length',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Peso (GR)
                                                <?=form_select(
                                                    'weight',$csvHeaders,($iF['weight']??null),[
                                                        'id' => 'weight',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Sinopse
                                                <?=form_select(
                                                    'synopsis',$csvHeaders,($iF['synopsis']??null),[
                                                        'id' => 'synopsis',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Capa
                                                <?=form_select(
                                                    'cover',$csvHeaders,($iF['cover']??null),[
                                                        'id' => 'cover',
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-4 pt-1">
                                                <input type="checkbox" name="download_cover" id="download_cover" class="custom-control-input">
                                                <label class="custom-control-label" for="download_cover">Fazer Download da Capa?</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            @csrf
                                            <input type="hidden" name="csv_file" value="{{$csvFile}}">
                                            <input type="hidden" name="delimiter" value="{{$delimiter}}">
                                            <button class="btn btn-block btn-outline-info float-right mt-3">Processar Dados</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="m-0">Produtos Importados</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                    <tr>
                                        <th>ISBN</th>
                                        <th>Titulo</th>
                                        <th>Editora</th>
                                        <th>Altura</th>
                                        <th>Largura</th>
                                        <th>Comprimento</th>
                                        <th>Peso</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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

@section('head')
    <link rel="stylesheet" href="{{asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('scripts')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
@endsection
