<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="content">
        <canvas id="chart1"></canvas>
    </div>
        <br>
        <br>
        Chart Type:
        <select id="type">
            <option value="line">Line</option>
            <option value="bar">Bar</option>
        </select>
        <select id="unit">
            <option value="second">Second</option>
            <option value="minute">Minute</option>
            <option value="hour">Hour</option>
            <option value="day" selected>Day</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
        </select>
        <button id="update">update</button>
    </div>
</div>

<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"
        type="application/javascript"></script>
<script src="{{ asset('utils.js') }}"></script>
<script>

    function generateData() {
        var unit = document.getElementById('unit').value;

        function unitLessThanDay() {
            return unit === 'second' || unit === 'minute' || unit === 'hour';
        }

        function beforeNineThirty(date) {
            return date.hour() < 9 || (date.hour() === 9 && date.minute() < 30);
        }

        // Returns true if outside 9:30am-4pm on a weekday
        function outsideMarketHours(date) {
            if (date.isoWeekday() > 5) {
                return true;
            }
            if (unitLessThanDay() && (beforeNineThirty(date) || date.hour() > 16)) {
                return true;
            }
            return false;
        }

        function randomNumber(min, max) {
            return Math.random() * (max - min) + min;
        }

        function randomBar(date, lastClose) {
            var open = randomNumber(lastClose * 0.95, lastClose * 1.05).toFixed(2);
            var close = randomNumber(open * 0.95, open * 1.05).toFixed(2);
            console.log(data.valueOf())

            return {
                t: date.valueOf(),
                y: close
            };

        }

        var date = moment('Jan 01 1990', 'MMM DD YYYY');
        var now = moment();
        var data = [];
        var lessThanDay = unitLessThanDay();
        for (; data.length < 600 && date.isBefore(now); date = date.clone().add(1, unit).startOf(unit)) {
            if (outsideMarketHours(date)) {
                if (!lessThanDay || !beforeNineThirty(date)) {
                    date = date.clone().add(date.isoWeekday() >= 5 ? 8 - date.isoWeekday() : 1, 'day');
                }
                if (lessThanDay) {
                    date = date.hour(9).minute(30).second(0);
                }
            }
            data.push(randomBar(date, data.length > 0 ? data[data.length - 1].y : 30));
        }

        return data;
    }

    var ctx = document.getElementById('chart1').getContext('2d');
    ctx.canvas.width = 1000;
    ctx.canvas.height = 300;

    var color = Chart.helpers.color;
    var cfg = {
        data: {
            datasets: [{
                label: 'CHRT - Chart.js Corporation',
                backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                borderColor: window.chartColors.red,
                data: generateData(),
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
                    afterBuildTicks: function(scale, ticks) {
                        var majorUnit = scale._majorUnit;
                        var firstTick = ticks[0];
                        var i, ilen, val, tick, currMajor, lastMajor;

                        val = moment(ticks[0].value);
                        if ((majorUnit === 'minute' && val.second() === 0)
                            || (majorUnit === 'hour' && val.minute() === 0)
                            || (majorUnit === 'day' && val.hour() === 9)
                            || (majorUnit === 'month' && val.date() <= 3 && val.isoWeekday() === 1)
                            || (majorUnit === 'year' && val.month() === 0)) {
                            firstTick.major = true;
                        } else {
                            firstTick.major = false;
                        }
                        lastMajor = val.get(majorUnit);

                        for (i = 1, ilen = ticks.length; i < ilen; i++) {
                            tick = ticks[i];
                            val = moment(tick.value);
                            currMajor = val.get(majorUnit);
                            tick.major = currMajor !== lastMajor;
                            lastMajor = currMajor;
                        }
                        return ticks;
                    }
                }],
                yAxes: [{
                    gridLines: {
                        drawBorder: false
                    },
                    scaleLabel: {
                        display: true,
                        labelString: 'Closing price ($)'
                    }
                }]
            },
            tooltips: {
                intersect: false,
                mode: 'index',
                callbacks: {
                    label: function(tooltipItem, myData) {
                        var label = myData.datasets[tooltipItem.datasetIndex].label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += parseFloat(tooltipItem.value).toFixed(2);
                        return label;
                    }
                }
            }
        }
    };

    var chart = new Chart(ctx, cfg);

    document.getElementById('update').addEventListener('click', function() {
        var type = document.getElementById('type').value;
        var dataset = chart.config.data.datasets[0];
        dataset.type = type;
        dataset.data = generateData();
        chart.update();
    });

</script>
</body>
</html>
