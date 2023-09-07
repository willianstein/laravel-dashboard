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
                                <h5 class="m-0">Etapa 2 - Mapeando as Informações</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{route('adm.stockImport.processCsv')}}" method="post" id="myForm">
                                    <?php $iF = session('importFields'); ?>
                                    <div class="form-row justify-content-end">

                                        <div class="form-group col-md-4">
                                            <label class="w-100">
                                                Tipo
                                                <?=form_select(
                                                    'type',$typeStock,null,[
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="w-100">
                                                Centro de Distribuição
                                                <?=form_select(
                                                    'office_id',$offices,null,[
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="w-100">
                                                Selecione o ISBN
                                                <?=form_select(
                                                    'isbn',$csvHeaders,($iF['isbn']??null),[
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione o Endereçamento
                                                <?=form_select(
                                                    'addressing',$csvHeaders,($iF['addressing']??null),[
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="w-100">
                                                Selecione a Quantidade
                                                <?=form_select(
                                                    'quantity',$csvHeaders,($iF['quantity']??null),[
                                                        'class' => 'custom-select'
                                                    ]
                                                );?>
                                            </label>
                                        </div>

                                        <div class="col-md-3 d-flex justify-content-center">
                                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success mt-4 pt-1">
                                                <input type="checkbox" name="save_addressing" id="save_addressing" class="custom-control-input">
                                                <label class="custom-control-label" for="save_addressing">Caso não existir o endereço, cadastra?</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            @csrf
                                            <input type="hidden" name="csv_file" value="{{$csvFile}}">
                                            <input type="hidden" name="delimiter" value="{{$delimiter}}">
                                            <input type="hidden" name="partner_id" value="{{$partner->id}}">
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
                                        <th>Produto</th>
                                        <th>ISBN</th>
                                        <th>Endereçamento</th>
                                        <th>Quantidade</th>
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
