@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Configurações / Integração</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-2">
                        @include('app.snippets.configMenu')
                    </div>
                    <div class="col-12 col-md-10 p-1">
                        <div class="d-flex justify-content-between flex-wrap mb-3">
                            <h5 class="m-0 p-0">Integrações Disponíveis</h5>
                        </div>

                        <div class="row">
                            <div class="col-1">
                                <a href="{{route('app.horusConfig.index')}}" class="btn btn-default btn-icon">
                                    <i class="fas fa-plug"></i>
                                    Horus
                                </a>
                            </div>
                            <div class="col-1">
                                <a href="#" class="btn btn-default btn-icon">
                                    <i class="fas fa-plug"></i>
                                    Versa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
