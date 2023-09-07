<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Laravel Highcharts Demo</title>

    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
</head>

<body>
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
                            <form method="post" id="myForm" accept-charset="UTF-8">
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
                                        <p class="info-box-footer w-100 m-0 text-center text-lg text-white">
                                            Movimentações
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
                                <div id="container" style=" width: 100%; height: 350px;">
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
</body>
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

<script>
    am5.ready(function () {
    // Create root element
    // https://www.amcharts.com/docs/v5/getting-started/#Root_element
    var root = am5.Root.new("chartdiv1");

    // Set themes
    // https://www.amcharts.com/docs/v5/concepts/themes/
    root.setThemes([am5themes_Animated.new(root)]);

    // Create chart
    // https://www.amcharts.com/docs/v5/charts/xy-chart/
    var chart = root.container.children.push(
        am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            layout: root.verticalLayout,
        })
    );

    // Add legend
    // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
    var legend = chart.children.push(
        am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50,
        })
    );

    // Create axes
    // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    var xRenderer = am5xy.AxisRendererX.new(root, {
        cellStartLocation: 0.1,
        cellEndLocation: 0.9,
    });

    var xAxis = chart.xAxes.push(
        am5xy.CategoryAxis.new(root, {
            categoryField: "time",
            renderer: xRenderer,
            tooltip: am5.Tooltip.new(root, {}),
        })
    );

    xRenderer.grid.template.setAll({
        location: 1,
    });

    var yAxis = chart.yAxes.push(
        am5xy.ValueAxis.new(root, {
            renderer: am5xy.AxisRendererY.new(root, {
                strokeOpacity: 0.1,
            }),
        })
    );

    function dataLoaded(result) {
        // Set data on all series of the chart
        var data = am5.JSONParser.parse(result.response);
        xAxis.data.setAll(data);
        result.target.series.each(function (series) {
            series.data.setAll(data);
        });
    }

    am5.net.load("/dashboards/orderTracking", chart).then(dataLoaded);


    const colors = [
        {
            entradadepedido: "#fd7e14",
            entradaexpedicao: "#90EE90",
            coletado: "#28a745",
            divergentes: "#ffc107",
            atrasados: "#dc3545",
        },
    ];

    chart.children.unshift(
        am5.Label.new(root, {
            text: "Monitoramento de pedidos por período",
            fontSize: 25,
            fontWeight: "500",
            textAlign: "center",
            x: am5.percent(50),
            centerX: am5.percent(50),
            paddingTop: 0,
            paddingBottom: 0,
        })
    );

    // Add series
    // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    function makeSeries(name, fieldName) {
        var series = chart.series.push(
            am5xy.ColumnSeries.new(root, {
                name: name,
                xAxis: xAxis,
                yAxis: yAxis,
                fill: colors[0][fieldName],
                valueYField: fieldName,
                categoryXField: "time",
            })
        );

        series.columns.template.setAll({
            tooltipText: "{name}, {categoryX}:{valueY}",
            width: am5.percent(90),
            tooltipY: 0,
            strokeOpacity: 0,
        });


        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear();

        series.bullets.push(function () {
            return am5.Bullet.new(root, {
                locationY: 0,
                sprite: am5.Label.new(root, {
                    text: "{valueY}",
                    fill: root.interfaceColors.get("alternativeText"),
                    centerY: 0,
                    centerX: am5.p50,
                    populateText: true,
                }),
            });
        });

        legend.data.push(series);
    }

    makeSeries("Entrada de pedido", "entradadepedido");
    makeSeries("Enviados para Expedição", "entradaexpedicao");
    makeSeries("Coletados", "coletado");
    makeSeries("Divergentes / Cancelados", "divergentes");
    makeSeries("Atrasados", "atrasados");



    root._logo.dispose();

    // Make stuff animate on load
    // https://www.amcharts.com/docs/v5/concepts/animations/
    chart.appear(5000, 100);
});

    </script>

</html>
