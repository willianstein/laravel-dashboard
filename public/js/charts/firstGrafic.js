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
