@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastros de Regras</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">

                    <!-- ITEMS -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">

                            <div div class="card-header">
                                <a href="{{route('roles.index')}}" class="btn btn-outline-primary">
                                    Voltar
                                </a>
                            </div>

                            <div class="card-body">


                                {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Nome:</strong>
                                            {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>Permissão:</strong>
                                            <br />
                                            @foreach ($permission as $value)
                                                <label>{{ Form::checkbox('permission[]', $value->id, false, ['class' => 'name']) }}
                                                    {{ $value->name }}</label>

                                                <br />
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </div>
                                </div>

                                {!! Form::close() !!}



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
            loadDataTable("{{ route('adm.user.getUsers') }}", 'table');
        });
    </script>
@endsection
