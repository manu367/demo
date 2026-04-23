<?php

$chart_name = ["PIE","BAR","LINE","AREA","SCATTER","COLUMN"];

/**
 * @param $chartname = this is name , which type of chart
 * @param $title = title of chart
 * @param $subtitle = subttial of chart
 * @param $align = which type of alignment (left , right , center)
 * @param $container = div ka name jisme render karna chahhata h user chart ko
 * @return null
 * @throws Exception
 */
function renderChart($chart,
                     $title,
                     $subtitle = '',
                     $align = 'center',
                     $container, $methodname,
                     $xAxis,
                     $yAxis,
                     $fms_id) {
    $methodname=$methodname.'_Chart';
    switch ($chart) {
        case 1:return barChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 2:return lineChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 3:return pieChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 4:return areaChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 5:return scatterChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 6:return geoChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 7:return funnelChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 8:return candleskChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 9:return gaugeChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 10:return choroplethChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        case 11:return columnChart($container, $title, $subtitle, $align = 'center',$methodname,$xAxis,$yAxis,$fms_id);
        default:
            throw new GlobalException("Chart type not handled");
    }
}

function choroplethChart($container, $title, $subtitle, string $param,$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);

}

function gaugeChart($container, $title, $subtitle, string $param,$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);

    $value = 65; // sample value (0–100)

    return "
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        Highcharts.chart('$container', {

            chart: {
                type: 'gauge'
            },

            title: {
                text: '$title'
            },

            subtitle: {
                text: '$subtitle'
            },

            pane: {
                startAngle: -150,
                endAngle: 150
            },

            yAxis: {
                min: 0,
                max: 100,

                tickPixelInterval: 30,

                plotBands: [{
                    from: 0,
                    to: 60,
                    color: '#55BF3B' // green
                }, {
                    from: 60,
                    to: 80,
                    color: '#DDDF0D' // yellow
                }, {
                    from: 80,
                    to: 100,
                    color: '#DF5353' // red
                }]
            },

            series: [{
                name: '$param',
                data: [$value],
                tooltip: {
                    valueSuffix: ' %'
                },
                dataLabels: {
                    format: '{y} %'
                }
            }]

        });

    });
    </script>
    ";
}

function candleskChart($container, $title, $subtitle, string $param,$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);
    // Sample OHLC data: [timestamp, open, high, low, close]
    $data = [
        [Date.UTC(2024, 0, 1), 100, 110, 90, 105],
        [Date.UTC(2024, 0, 2), 105, 115, 95, 110],
        [Date.UTC(2024, 0, 3), 110, 120, 100, 115],
        [Date.UTC(2024, 0, 4), 115, 125, 105, 120],
        [Date.UTC(2024, 0, 5), 120, 130, 110, 125]
    ];

    $jsonData = json_encode($data);

    return "
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        Highcharts.stockChart('$container', {

            rangeSelector: {
                selected: 1
            },

            title: {
                text: '$title'
            },

            subtitle: {
                text: '$subtitle'
            },

            series: [{
                type: 'candlestick',
                name: '$param',
                data: $jsonData,
                tooltip: {
                    valueDecimals: 2
                }
            }]

        });

    });
    </script>
    ";
}

function funnelChart($container, $title, $subtitle, string $param,$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);

    $data = [
        ["Website Visits", 15654],
        ["Downloads", 4064],
        ["Requested Price List", 1987],
        ["Invoice Sent", 976],
        ["Finalized", 846]
    ];

    $jsonData = json_encode($data);

    return "
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        Highcharts.chart('$container', {

            chart: {
                type: 'funnel'
            },

            title: {
                text: '$title'
            },

            subtitle: {
                text: '$subtitle'
            },

            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}',
                        softConnector: true
                    },
                    center: ['50%', '50%'],
                    width: '80%',
                    neckWidth: '30%',
                    neckHeight: '25%'
                }
            },

            legend: {
                enabled: false
            },

            series: [{
                name: '$param',
                data: $jsonData
            }]

        });

    });
    </script>
    ";
}


function geoChart($container, $title, $subtitle, string $param,$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);

    return "
    <div style='width:100%;'>
        <h3 style='margin:0;text-align: center;text-transform: uppercase';>$title</h3>
        <p style='margin:0 0 10px 0; color:#666;text-align: center;text-transform: uppercase;'>$subtitle</p>
        <div id='$container' style='width:100%; height:500px;'></div>
    </div>

    <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
    <script type='text/javascript'>
    
    google.charts.load('current', {'packages':['geochart']});
    google.charts.setOnLoadCallback(drawRegionsMap);

    function drawRegionsMap() {

        var data = google.visualization.arrayToDataTable([
            ['Country', '$param'],
            ['Germany', 200],
            ['United States', 300],
            ['India', 700],
            ['France', 600]
        ]);

        var options = {
            colorAxis: {colors: ['#e0f3f8', '#08589e']},
            datalessRegionColor: '#eeeeee'
        };

        var chart = new google.visualization.GeoChart(document.getElementById('$container'));
        chart.draw(data, options);
    }
    </script>
    ";
}


function scatterChart($containerId, $title, $subtitle, $align = 'left',$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);

    $series = [
        [
            "data" => [
                ["x"=>95,"y"=>95,"z"=>13.8,"name"=>"BE","country"=>"Belgium"],
                ["x"=>86.5,"y"=>102.9,"z"=>14.7,"name"=>"DE","country"=>"Germany"],
                ["x"=>80.8,"y"=>91.5,"z"=>15.8,"name"=>"FI","country"=>"Finland"],
                ["x"=>80.4,"y"=>102.5,"z"=>12,"name"=>"NL","country"=>"Netherlands"],
                ["x"=>80.3,"y"=>86.1,"z"=>11.8,"name"=>"SE","country"=>"Sweden"],
                ["x"=>78.4,"y"=>70.1,"z"=>16.6,"name"=>"ES","country"=>"Spain"],
                ["x"=>74.2,"y"=>68.5,"z"=>14.5,"name"=>"FR","country"=>"France"],
                ["x"=>73.5,"y"=>83.1,"z"=>10,"name"=>"NO","country"=>"Norway"],
                ["x"=>71,"y"=>93.2,"z"=>24.7,"name"=>"UK","country"=>"UK"],
                ["x"=>69.2,"y"=>57.6,"z"=>10.4,"name"=>"IT","country"=>"Italy"],
                ["x"=>68.6,"y"=>20,"z"=>16,"name"=>"RU","country"=>"Russia"],
                ["x"=>65.5,"y"=>126.4,"z"=>35.3,"name"=>"US","country"=>"USA"],
                ["x"=>65.4,"y"=>50.8,"z"=>28.5,"name"=>"HU","country"=>"Hungary"],
                ["x"=>63.4,"y"=>51.8,"z"=>15.4,"name"=>"PT","country"=>"Portugal"],
                ["x"=>64,"y"=>82.9,"z"=>31.3,"name"=>"NZ","country"=>"New Zealand"]
            ],
            "colorByPoint" => true
        ],
    ];

    $jsonSeries = json_encode($series);
    return "
    <script>
    Highcharts.chart('$containerId', {
        chart: {
            type: 'bubble',
            plotBorderWidth: 1,
            zooming: { type: 'xy' }
        },
        title: {
            text: '$title',
            align: '$align'
        },
        subtitle: {
            text: '$subtitle'
        },
        legend: {
            enabled: false
        },
        xAxis: {
            gridLineWidth: 1,
            title: { text: 'Daily fat intake' },
            labels: { format: '{value} gr' }
        },
        yAxis: {
            title: { text: 'Daily sugar intake' },
            labels: { format: '{value} gr' }
        },
        tooltip: {
            useHTML: true,
            pointFormat:
                '<b>{point.country}</b><br>' +
                'Fat: {point.x}g<br>' +
                'Sugar: {point.y}g<br>' +
                'Obesity: {point.z}%'
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: $jsonSeries
    });
    </script>
    ";
}

function columnChart($containerId, $title, $subtitle, $align = 'left',$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);

    $series = [
        [
            "name" => "Norway",
            "data" => [148, 133, 124],
            "stack" => "Europe"
        ],
        [
            "name" => "Germany",
            "data" => [102, 98, 65],
            "stack" => "Europe"
        ],
        [
            "name" => "United States",
            "data" => [113, 122, 95],
            "stack" => "North America"
        ],
        [
            "name" => "Canada",
            "data" => [77, 72, 80],
            "stack" => "North America"
        ]
    ];

    $jsonSeries = json_encode($series);

    return "
    <script>
    Highcharts.chart('$containerId', {
        chart: {
            type: 'column'
        },
        title: {
            text: '$title',
            align: '$align'
        },
        subtitle: {
            text: '$subtitle'
        },
        xAxis: {
            categories: ['Gold', 'Silver', 'Bronze']
        },
        yAxis: {
            allowDecimals: false,
            min: 0,
            title: {
                text: 'Count medals'
            }
        },
        tooltip: {
            format: '<b>{key}</b><br/>{series.name}: {y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal'
            }
        },
        series: $jsonSeries
    });
    </script>
    ";
}

function areaChart($containerId, $title, $subtitle, $align = 'left',$methodname,$xAxis,$yAxis,$fms_id)
{
    $series = [
        [
            "name" => "USA",
            "data" => [
                null, null, null, null, null, 2, 9, 13, 50, 170, 299, 438, 841,
                1169, 1703, 2422, 3692, 5543, 7345, 12298, 18638, 22229, 25540,
                28133, 29463, 31139, 31175, 31255, 29561, 27552, 26008, 25830,
                26516, 27835, 28537, 27519, 25914, 25542, 24418, 24138, 24104,
                23208, 22886, 23305, 23459, 23368, 23317, 23575, 23205, 22217,
                21392, 19008, 13708, 11511, 10979, 10904, 11011, 10903, 10732,
                10685, 10577, 10526, 10457, 10027, 8570, 8360, 7853, 5709, 5273,
                5113, 5066, 4897, 4881, 4804, 4717, 4571, 4018, 3822, 3785, 3805,
                3750, 3708, 3708, 3708, 3708
            ]
        ],
        [
            "name" => "USSR/Russia",
            "data" => [
                null, null, null, null, null, null, null, null, null,
                1, 5, 25, 50, 120, 150, 200, 426, 660, 863, 1048, 1627, 2492,
                3346, 4259, 5242, 6144, 7091, 8400, 9490, 10671, 11736, 13279,
                14600, 15878, 17286, 19235, 22165, 24281, 26169, 28258, 30665,
                32146, 33486, 35130, 36825, 38582, 40159, 38107, 36538, 35078,
                32980, 29154, 26734, 24403, 21339, 18179, 15942, 15442, 14368,
                13188, 12188, 11152, 10114, 9076, 8038, 7000, 6643, 6286, 5929,
                5527, 5215, 4858, 4750, 4650, 4600, 4500, 4490, 4300, 4350, 4330,
                4310, 4495, 4477, 4489, 4380
            ]
        ]
    ];
    $getData=dynamicBinding($methodname,$fms_id);
    var_dump($methodname,$xAxis,$yAxis,$fms_id);
    var_dump($getData);

    $jsonSeries = json_encode($series);

    return "
    <script>
    Highcharts.chart('$containerId', {
        chart: {
            type: 'area'
        },
        title: {
            text: '$title',
            align: '$align'
        },
        subtitle: {
            text: '$subtitle'
        },
        xAxis: {
            allowDecimals: false,
            accessibility: {
                rangeDescription: 'Range: 1940 to 2024'
            }
        },
        yAxis: {
            title: {
                text: 'Nuclear weapon states'
            }
        },
        tooltip: {
            pointFormat: '{series.name} had <b>{point.y:,.0f}</b> warheads in {point.x}'
        },
        plotOptions: {
            area: {
                pointStart: 1940,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: $jsonSeries
    });
    </script>
    ";
}

function lineChart($containerId, $title, $subtitle, $align = 'left',$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);

    $series = [
        [
            "name" => "Installation & Developers",
            "data" => [43934, 48656, 65165, 81827, 112143, 142383,
                171533, 165174, 155157, 161454, 154610, 168960, 171558]
        ],
        [
            "name" => "Manufacturing",
            "data" => [24916, 37941, 29742, 29851, 32490, 30282,
                38121, 36885, 33726, 34243, 31050, 33099, 33473]
        ],
        [
            "name" => "Sales & Distribution",
            "data" => [11744, 30000, 16005, 19771, 20185, 24377,
                32147, 30912, 29243, 29213, 25663, 28978, 30618]
        ],
        [
            "name" => "Operations & Maintenance",
            "data" => [null, null, null, null, null, null, null,
                null, 11164, 11218, 10077, 12530, 16585]
        ],
        [
            "name" => "Other",
            "data" => [21908, 5548, 8105, 11248, 8989, 11816, 18274,
                17300, 13053, 11906, 10073, 11471, 11648]
        ]
    ];

    $jsonSeries = json_encode($getData);

    return "
    <script>
    Highcharts.chart('$containerId', {
        chart: {
            type: 'line'
        },
        title: {
            text: '$title',
            align: '$align'
        },
        subtitle: {
            text: '$subtitle',
            align: '$align'
        },
        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },
        xAxis: {
            accessibility: {
                rangeDescription: 'Range: 2010 to 2022'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                pointStart: 2010
            }
        },
        series: $jsonSeries,
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
    ";
}

function barChart($containerId, $title, $subtitle, $align = 'left',$methodname,$xAxis,$yAxis,$fms_id)
{
    $getData=dynamicBinding($methodname,$fms_id);
//    $series = [
//        [
//            "name" => "Year 1990",
//            "data" => [632, 727, 3202, 721]
//        ],
//        [
//            "name" => "Year 2000",
//            "data" => [814, 841, 3714, 726]
//        ],
//        [
//            "name" => "Year 2021",
//            "data" => [1393, 1031, 4695, 745]
//        ]
//    ];
    $customization=[];

    $jsonSeries = json_encode($getData);

    return "
    <script>
    Highcharts.chart('$containerId', {
        chart: {
            type: 'bar'
        },
        title: {
            text: '$title',
            align: '$align'
        },
        subtitle: {
            text: '$subtitle'
        },
        xAxis: {
            categories: ['1', '2', '3', '4'],
            title: { text: null },
            gridLineWidth: 1,
            lineWidth: 0
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Population (millions)',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            },
            gridLineWidth: 0
        },
        tooltip: {
            valueSuffix: ' millions'
        },
        plotOptions: {
            bar: {
                borderRadius: '50%',
                dataLabels: {
                    enabled: true
                },
                groupPadding: 0.1
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: '#ffffff',
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: $jsonSeries
    });
    </script>
    ";
}

function pieChart($containerId, $title, $subtitle, $align = 'left',$methodname,$xAxis,$yAxis,$fms_id){

    $getData=dynamicBinding($methodname,$fms_id);

    $data = [
        [
            "name" => $title,
            "colorByPoint" => true,
            "data" => [
                ["name"=>"Water","y"=>44.02],
                ["name"=>"Fat","y"=>26.71],
                ["name"=>"Carbohydrates","y"=>29.27]
            ]
        ]
    ];

    $jsonData = json_encode($getData);

    return "
    <script>
    Highcharts.chart('$containerId', {
        chart: {
            type: 'pie'
        },
        title: {
            text: '$title',
            align: '$align'
        },
        subtitle: {
            text: '$subtitle'
        },
        tooltip: {
            valueSuffix: '%'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: [{
                    enabled: true,
                    distance: 20
                }, {
                    enabled: true,
                    distance: -40,
                    format: '{point.percentage:.1f}%',
                    style: {
                        fontSize: '1.2em',
                        textOutline: 'none',
                        opacity: 0.7
                    },
                    filter: {
                        operator: '>',
                        property: 'percentage',
                        value: 10
                    }
                }]
            }
        },
        series: $jsonData
    });
    </script>
    ";
}