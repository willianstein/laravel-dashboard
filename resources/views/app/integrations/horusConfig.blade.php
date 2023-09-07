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
                            <h5 class="m-0 p-0">Integração Horus</h5>
                        </div>

                        <div class="row">
                            <div class="col">
                                <form id="myForm" method="post" class="ajax-off" action="{{route('app.integration.save')}}">
                                    <div class="card card-default">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                                                    Dados de Acesso
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="collapse" data-parent="#myForm" style="">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-12">
                                                        <?=form_select('partner_id',($dropPartners??null),null,[
                                                            'id' => 'partner_id',
                                                            'placeholder' => 'Parceiro',
                                                            'class' => 'form-control select2 getTokens',
                                                            'data-toggle' => 'tooltip'
                                                        ],'Selecione o Parceiro');?>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-12">
                                                        <input type="text" name="url" id="url" placeholder="Endereço do Servidor" data-toggle="tooltip" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <input type="text" name="user" id="user" placeholder="Usuário" data-toggle="tooltip" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <input type="password" name="password" id="password" placeholder="Senha" data-toggle="tooltip" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group mb-md-0 col-12">
                                                        <input type="text" name="token" id="token" placeholder="Token" data-toggle="tooltip" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card card-default">
                                        <div class="card-header">
                                            <h4 class="card-title w-100">
                                                <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                                    Configuração dos Pedidos
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="collapse" data-parent="#myForm">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group mb-md-0 col-md-6">
                                                        <input type="text" name="COD_EMPRESA" id="COD_EMPRESA" value="1" placeholder="Código da Empresa" data-toggle="tooltip" class="form-control">
                                                    </div>
                                                    <div class="form-group mb-md-0 col-md-6">
                                                        <input type="text" name="COD_FILIAL" id="COD_FILIAL" value="1" placeholder="Código da Filial" data-toggle="tooltip" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        @csrf
                                        <input type="hidden" name="name" value="Horus">
                                        <input type="hidden" name="driver" value="\App\Http\Libraries\Integrators\HorusIntegrator">
                                        <button type="submit" class="btn btn-danger"> Salvar Integração </button>
                                    </div>
                                </form>
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
