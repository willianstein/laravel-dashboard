@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastros Endereçamentos</h1>
                    </div><!-- /.col -->
                    @can('imprimir-etiqueta-enderecamento')
                        <div class="col-sm-6">
                            <a href="{{ route('adm.addressing.selectTags') }}" class="btn btn-outline-info float-right">
                                <i class="fas fa-print"></i>
                                <span class="text-bold">Imprimir Etiquetas</span>
                            </a>
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
                    @can('cadastrar-enderecamentos')
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h5 class="m-0">Manutenção do Cadastro</h5>
                                </div>
                                <div class="card-body">
                                    <form method="post" id="myForm">
                                        <div class="form-row">

                                            <div class="form-group col-md-3">
                                                <select name="office_id" id="office_id" class="custom-select"
                                                    placeholder="Unidade" data-toggle="tooltip">
                                                    <option value="">Selecione</option>
                                                    @if (!empty($offices))
                                                        @foreach ($offices as $office)
                                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="form-group col-md-5">
                                                <input type="text" name="name" id="name" placeholder="Endereçamento"
                                                    data-toggle="tooltip" class="form-control">
                                            </div>

                                            <div class="form-group col-md-2">
                                                <input type="number" name="distance" id="distance" placeholder="Distância"
                                                    data-toggle="tooltip" class="form-control" step="1">
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
                                <h5 class="m-0">Últimos Serviços Cadastrados</h5>
                            </div>
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>Unidade</th>
                                            <th>Endereçamento</th>
                                            <th>Distância</th>
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
            loadDataTable("{{ route('adm.addressing.getAddressings') }}", 'table');
        });
    </script>
@endsection
