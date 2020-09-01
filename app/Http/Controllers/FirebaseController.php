<?php

namespace App\Http\Controllers;

use App\SensorData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FirebaseController extends Controller
{
    private $database;

    public function __construct()
    {
        $this->database = app('firebase.database');
        date_default_timezone_set('Asia/Kuala_Lumpur');
    }

    public function index($type)
    {
        if(!$type){
            return abort('404', 'Invalid Chart type');
        }

        $data = $this->database->getReference('data_push')->orderByChild('timestamp')->getSnapshot()->getValue();

        $array = [];

        foreach ($data as $datum) {
            $value = [
                't' => Carbon::createFromTimestamp($datum['timestamp'])->toDateTimeString(),
                'y' => $datum[$type]
            ];

            array_push($array, $value);
        }

        return response()->json($array);
    }

    public function realtime($type)
    {
        if(!$type){
            return abort('404', 'Invalid Chart type');
        }

        $reference = $this->database->getReference('data_push')
            ->orderByChild('timestamp')
            ->limitToLast(1);

        $snapshot = $reference->getSnapshot();
        $data = $snapshot->getValue();

        $array = [];

        foreach ($data as $datum) {
            $last_record = SensorData::latest()->first();

            if($last_record == NULL || $last_record->timestamp !== number_format($datum['timestamp'],0,'.','')){
                DB::select("call insert_sensor_data_proc('".$datum['DHT22_temp']."','".$datum['DHT22_hum']."','".$datum['DS18B20_temp']."','".$datum['soilMoister']."','".$datum['timestamp']."')");
            }

            $value = [
                't' => Carbon::createFromTimestamp($datum['timestamp'])->toDateTimeString(),
                'y' => $datum[$type]
            ];

            array_push($array, $value);
        }

        return response()->json($array);
    }
}
