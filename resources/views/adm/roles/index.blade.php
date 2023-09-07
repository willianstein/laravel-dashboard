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
                            @can('cadastro-regras')
                                <div class="card-header">
                                    <a href="{{ route('roles.create') }}" class="btn btn-outline-primary">
                                        Criar Nova Regra
                                    </a>
                                </div>
                            @endcan
                            <div class="card-body">
                                <table id="table" class="table table-striped dataTable dtr-inline">
                                    <tr>
                                        <th>Nome da Regra</th>
                                        <th width="280px">Ações</th>
                                    </tr>
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                @can('ver-regras')
                                                    <a class="btn btn-success"
                                                        href="{{ route('roles.show', $role->id) }}">Ver</a>
                                                @endcan
                                                @can('editar-regras')
                                                    <a class="btn btn-warning"
                                                        href="{{ route('roles.edit', $role->id) }}">Editar</a>
                                                @endcan
                                                @can('deletar-regras')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                                    {!! Form::submit('Deletar', ['class' => 'btn btn-danger']) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
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
            loadDataTable("{{ route('adm.user.getUsers') }}", 'table');
        });
    </script>
@endsection
