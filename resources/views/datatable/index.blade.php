@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Sensor Data</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table id="example" class="display" style="width:100%">
                            <thead>
                            <tr>
                                <th>Air Temperature (°C)</th>
                                <th>Air humidity</th>
                                <th>Soil Temperature (°C)</th>
                                <th>Soil Humidity</th>
                                <th>Timestamp</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $datum)
                                <tr>
                                    <td>{{ $datum->air_temp }}</td>
                                    <td>{{ $datum->air_humid }}</td>
                                    <td>{{ $datum->soil_temp }}</td>
                                    <td>{{ $datum->soil_humid }}</td>
                                    <td>{{ $datum->timestamp->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Air Temperature (°C)</th>
                                <th>Air humidity</th>
                                <th>Soil Temperature (°C)</th>
                                <th>Soil Humidity</th>
                                <th>Timestamp</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
