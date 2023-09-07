@extends('layouts.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container">
                <!-- Resumos -->
{{--                <div class="row">--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="info-box my-info p-3">--}}
{{--                            <span class="info-box-icon bg-gradient-cyan"><i class="fas fa-sign-in-alt"></i></span>--}}
{{--                            <div class="info-box-content align-items-end">--}}
{{--                                <span class="info-box-text">Recebidos</span>--}}
{{--                                <span class="info-box-number">150/300</span>--}}
{{--                            </div>--}}
{{--                            <hr class="w-100 my-2">--}}
{{--                            <p class="info-box-footer w-100 m-0">No Pedido de Entrada</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="info-box my-info p-3">--}}
{{--                            <span class="info-box-icon bg-gradient-teal"><i class="fas fa-tasks"></i></span>--}}
{{--                            <div class="info-box-content align-items-end">--}}
{{--                                <span class="info-box-text">Conferidos</span>--}}
{{--                                <span class="info-box-number">120/300</span>--}}
{{--                            </div>--}}
{{--                            <hr class="w-100 my-2">--}}
{{--                            <p class="info-box-footer w-100 m-0">No Pedido de Entrada</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="info-box my-info p-3">--}}
{{--                            <span class="info-box-icon bg-gradient-green"><i class="fas fa-location-arrow"></i></span>--}}
{{--                            <div class="info-box-content align-items-end">--}}
{{--                                <span class="info-box-text">Endereçados</span>--}}
{{--                                <span class="info-box-number">150/300</span>--}}
{{--                            </div>--}}
{{--                            <hr class="w-100 my-2">--}}
{{--                            <p class="info-box-footer w-100 m-0">No Pedido de Entrada</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="info-box my-info p-3">--}}
{{--                            <span class="info-box-icon bg-gradient-info"><i class="fas fa-code-branch"></i></span>--}}
{{--                            <div class="info-box-content align-items-end">--}}
{{--                                <span class="info-box-text">Separados</span>--}}
{{--                                <span class="info-box-number">150/300</span>--}}
{{--                            </div>--}}
{{--                            <hr class="w-100 my-2">--}}
{{--                            <p class="info-box-footer w-100 m-0">No Pedido de Saida</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="info-box my-info p-3">--}}
{{--                            <span class="info-box-icon bg-gradient-blue"><i class="fas fa-check-double"></i></span>--}}
{{--                            <div class="info-box-content align-items-end">--}}
{{--                                <span class="info-box-text">Conferidos</span>--}}
{{--                                <span class="info-box-number">150/300</span>--}}
{{--                            </div>--}}
{{--                            <hr class="w-100 my-2">--}}
{{--                            <p class="info-box-footer w-100 m-0">No Pedido de Saida</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-4">--}}
{{--                        <div class="info-box my-info p-3">--}}
{{--                            <span class="info-box-icon bg-gradient-purple"><i class="fas fa-truck-moving"></i></span>--}}
{{--                            <div class="info-box-content align-items-end">--}}
{{--                                <span class="info-box-text">Despachados</span>--}}
{{--                                <span class="info-box-number">150/300</span>--}}
{{--                            </div>--}}
{{--                            <hr class="w-100 my-2">--}}
{{--                            <p class="info-box-footer w-100 m-0">No Pedido de Saida</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <!-- Acompanhamento Projetos -->
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="card card-primary card-outline">--}}
{{--                            <div class="card-header">--}}
{{--                                <h5 class="m-0">Andamento dos Projetos</h5>--}}
{{--                            </div>--}}
{{--                            <div class="card-body">--}}
{{--                                <div class="chart">--}}
{{--                                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')

@endsection
