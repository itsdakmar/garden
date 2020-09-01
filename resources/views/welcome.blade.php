<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pricing example for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        html {
            font-size: 14px;
        }
        @media (min-width: 768px) {
            html {
                font-size: 16px;
            }
        }

        .container {
            max-width: 960px;
        }

        .pricing-header {
            max-width: 700px;
        }

        .card-deck .card {
            min-width: 220px;
        }

        .border-top { border-top: 1px solid #e5e5e5; }
        .border-bottom { border-bottom: 1px solid #e5e5e5; }
        .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
    </style>
</head>

<body>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <h5 class="my-0 mr-md-auto font-weight-normal">Smart Garden Report</h5>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="#">Chart</a>
        <a class="p-2 text-dark" href="#">Datatable</a>
        <a class="p-2 text-dark" href="#">Alert / Warning</a>
    </nav>
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Smart Garden Report</h1>
    <p class="lead">Quickly build an effective pricing table for your potential customers with this Bootstrap example. It's built with default Bootstrap components and utilities with little customization.</p>
    <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
            <a class="nav-link {{ (request()->segment(1) == 'air-temp') ? 'active' : '' }}" href="{{ route('chart', 'air-temp') }}">Air Temperature</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (request()->segment(1) == 'air-humid') ? 'active' : '' }}" href="{{ route('chart', 'air-humid') }}">Air Humidity</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (request()->segment(1) == 'soil-temp') ? 'active' : '' }}" href="{{ route('chart', 'soil-temp') }}">Soil Temperature</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ (request()->segment(1) == 'soil-humid') ? 'active' : '' }}" href="{{ route('chart', 'soil-humid') }}">Soil Humidity</a>
        </li>
    </ul>
</div>

<div class="container">
    <canvas id="myChart" data-type="{{ $type }}"></canvas>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"
        type="application/javascript"></script>
<script src="{{ asset('utils.js') }}"></script>
<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/holder.min.js"></script>
<script>
    Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
    });
</script>

<script>
    let chartType = $('#myChart').data('type');

    function createChart(data) {
        var ctx = document.getElementById('myChart').getContext('2d');
        ctx.canvas.width = 1000;
        ctx.canvas.height = 300;

        var color = Chart.helpers.color;

        var cfg = {
            data: {
                datasets: [{
                    label: chartType,
                    backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.red,
                    data: data,
                    type: 'line',
                    pointRadius: 0,
                    fill: false,
                    lineTension: 0,
                    borderWidth: 2
                }]
            },
            options: {
                animation: {
                    duration: 0
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        distribution: 'series',
                        offset: true,
                        ticks: {
                            major: {
                                enabled: true,
                                fontStyle: 'bold'
                            },
                            source: 'data',
                            autoSkip: true,
                            autoSkipPadding: 75,
                            maxRotation: 0,
                            sampleSize: 100
                        },
                        time: {
                            unit: 'second',
                            displayFormats: {
                                second: 'h:mm:ss a'
                            },
                        },
                    }],
                    yAxes: [{
                        gridLines: {
                            drawBorder: false
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Air Temperature (°C)'
                        }
                    }]
                },
                tooltips: {
                    intersect: false,
                    mode: 'index',
                    callbacks: {
                        label: function (tooltipItem, myData) {
                            var label = myData.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += parseFloat(tooltipItem.value).toFixed(2) + '°C';
                            return label;
                        }
                    }
                },
            }
        };
        var chart = new Chart(ctx, cfg);
        var lastDate = null;

        setInterval(function () {
            $.getJSON('http://garden.test/firebase/realtime/'+chartType).done(function (data) {
                if (lastDate === null) {
                    chart.data.datasets[0].data.push(data[0]);
                    chart.update({
                        duration: 800,
                        easing: 'easeInOutCubic'
                    });
                    lastDate = data[0].t;
                    console.log(data[0].y);
                } else if(lastDate !== data[0].t) {
                    chart.data.datasets[0].data.push(data[0]);
                    chart.update({
                        duration: 800,
                        easing: 'easeInOutCubic'
                    });
                    lastDate = null;
                    console.log(data[0].y);
                }else {
                    console.log('No updated data.')
                }
            });
        }, 6000);
    }

    $.getJSON('http://garden.test/firebase/'+chartType).done(function (data) {
        createChart(data);
    });
</script>
</body>
</html>
