<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

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

            $value = [
                't' => Carbon::createFromTimestamp($datum['timestamp'])->toDateTimeString(),
                'y' => $datum[$type]
            ];

            array_push($array, $value);
        }

        return response()->json($array);
    }
}
