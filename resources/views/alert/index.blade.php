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
                                <th>Value</th>
                                <th>Sensor</th>
                                <th>Message</th>
                                <th>Timestamp</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $datum)
                                <tr>
                                    <td>{{ $datum->value }}</td>
                                    <td>{{ $datum->sensor }}</td>
                                    <td>{{ $datum->message }}</td>
                                    <td>{{ $datum->timestamp->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Value</th>
                                <th>Sensor</th>
                                <th>Message</th>
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
