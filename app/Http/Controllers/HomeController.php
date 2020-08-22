<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index($type = 'air-temp')
    {
        switch ($type) {
            case "air-temp":
                $type = 'DHT22_temp';
                break;
            case "air-humid":
                $type = 'DHT22_hum';
                break;
            case "soil-temp":
                $type = 'DS18B20_temp';
                break;
            case "soil-humid":
                $type = 'soilMoister';
                break;
            default:
                $type = 'DHT22_temp';
        }

        return view('welcome' , compact('type'));
    }
}
