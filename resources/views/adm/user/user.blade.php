@extends('layouts.adm')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Cadastros Usuários</h1>
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
                            {{-- @can('cadastrar-usuario') --}}
                                <div class="card-header">
                                    <a href="{{ route('adm.user.create') }}" class="btn btn-outline-primary">
                                        Criar Novo Usuário
                                    </a>
                                </div>
                            {{-- @endcan --}}
                            {{-- @can('ver-usuario') --}}
                                <div class="card-body">
                                    <table id="table" class="table table-striped dtr-inline">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>E-mail</th>
                                                <th>Regra</th>
                                                <th>Ativo</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($users)
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td> {{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>
                                                            @if (!empty($user->getRoleNames()))
                                                                @foreach ($user->getRoleNames() as $v)
                                                                    <label
                                                                        class="badge badge-success">{{ $v }}</label>
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        {{-- @can('inativar-usuario') --}}
                                                            <td>
                                                                <div
                                                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                                    <input type="checkbox" class="custom-control-input ajax-check"
                                                                        data-url={{ route('adm.user.onOff', ['user' => $user->id]) }}
                                                                        id="active_contact_{{ $user->id }}"
                                                                        {{ empty($user->active) ?: 'checked' }}>

                                                                    <label class="custom-control-label"
                                                                        for="active_contact_{{ $user->id }}">Ativo?
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        {{-- @else --}}
                                                            <td>
                                                            </td>
                                                        {{-- @endcan --}}
                                                        {{-- @can('editar-usuario') --}}
                                                            <td>
                                                                <a href="{{ route('adm.editUser', ['user' => $user->id]) }}" class="badge badge-success">
                                                                    Editar Usuário
                                                                </a>
                                                                {{-- <span class="badge badge-success ajax-link" data-obj="myForm"
                                                                    data-url={{ route('adm.user.getUser', ['user' => $user->id]) }}><i
                                                                        class="fas fa-pen\"></i> EDITAR
                                                                </span> --}}
                                                            </td>
                                                        {{-- @else --}}
                                                            <td>
                                                            </td>
                                                        {{-- @endcan --}}
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            {{-- @endcan --}}
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
@endsection

@section('scripts')
    <script>
        // $(document).ready(function() {
        //     loadDataTable("{{ route('adm.user.getUsers') }}", 'table');
        // });
    </script>
@endsection
