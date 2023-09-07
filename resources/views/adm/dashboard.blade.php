@extends('layouts.adm')



@section('content')
    <script src="{{ asset('js/charts/firstGrafic.js') }}"></script>
    {{-- <script src="{{ asset('js/charts/twoChart.js') }}"></script> --}}

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <div class="content">
            <div class="container-fluid">

                <div class="col-md-12">
                    <div class="card card-primary card-outline">

                        <div class="card-body">
                            <form method="post" id="myForm" action="{{ route('adm.user.save') }}"
                                accept-charset="UTF-8">
                                <div class="form-row">
                                    <div class="form-group col-4">
                                        <span class="info-box-text ">Data Início</span>
                                        <input type="date" name="forecast" id="forecast"
                                            placeholder="Previsão de Chegada" class="form-control">
                                    </div>
                                    <div class="form-group col-4">
                                        <span class="info-box-text ">Data Fim</span>
                                        <input type="date" name="forecast" id="forecast"
                                            placeholder="Previsão de Chegada" class="form-control">
                                    </div>
                                    {{-- <div class="form-group col-3">
                                <span class="info-box-text ">Buscar Pedido</span>
                                <div class="input-group">
                                    <input type="text" name="title" id="title" placeholder="Título"
                                        class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                </div>
                            </div> --}}
                                    <div class="form-group col-4">
                                        <br>
                                        @csrf
                                        <input type="hidden" name="partner_id" id="partner_id"
                                            value="<?= $partner->id ?? null ?>">
                                        <button class="btn btn-outline-info btn-lg btn-block">Buscar Dados</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-outline">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="info-box my-info bg-orange p-3">
                                        <div class="info-box-content align-items-center">
                                            {{-- <span class="info-box-text ">Recebidos</span> --}}
                                            <span class="info-box-number text-xl text-white">{{ $movimentacoes }}</span>
                                        </div>
                                        <hr class="w-100 my-2">
                                        <p class="info-box-footer w-100 m-0 text-center text-lg text-white">Movimentações
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box my-info bg-green p-3">
                                        <div class="info-box-content align-items-center">
                                            {{-- <span class="info-box-text ">Recebidos</span> --}}
                                            <span class="info-box-number text-xl">{{ $tranport }}</span>
                                        </div>
                                        <hr class="w-100 my-2">
                                        <p class="info-box-footer w-100 m-0 text-center text-lg ">Enviados Expedição</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box my-info bg-yellow p-3">
                                        <div class="info-box-content align-items-center">
                                            {{-- <span class="info-box-text ">Recebidos</span> --}}
                                            <span class="info-box-number text-xl text-white">{{ $cancel }}</span>
                                        </div>
                                        <hr class="w-100 my-2">
                                        <p class="info-box-footer w-100 m-0 text-center text-lg text-white">
                                            Divergentes/Cancelados</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-box my-info  bg-red p-3">

                                        <div class="info-box-content align-items-center">
                                            {{-- <span class="info-box-text text-xl">Separados</span> --}}
                                            <span class="info-box-number text-xl">{{ $atrasados }}</span>
                                        </div>
                                        <hr class="w-100 my-2">
                                        <p class="info-box-footer w-100 m-0 text-center text-lg">Atrasados</p>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div id="chartdiv1" style=" width: 100%; height: 350px;">
                                </div>
                                {{-- <div id="chartContainer" style="height: 370px; width: 100%;"></div> --}}
                                {{-- <div id="chartdiv" style=" width: 40%; height: 350px;">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card card-outline">
                        <div class="card-body">
                            <div class="row">
                                <div  id="container" style=" width: 100%; height: 350px;">
                                </div>

                                {{-- <div id="chartdiv4" style=" width: 50%; height: 350px;">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        var userData = <?php echo json_encode($userData); ?>;
        Highcharts.chart('container', {
            title: {
                text: 'New User Growth, 2020'
            },
            subtitle: {
                text: 'Source: positronx.io'
            },
            xAxis: {
                categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                    'October', 'November', 'December'
                ]
            },
            yAxis: {
                title: {
                    text: 'Number of New Users'
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    allowPointSelect: true
                }
            },
            series: [{
                name: 'New Users',
                data: userData
            }],
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    </script>
@endsection

@section('scripts')
@endsection
