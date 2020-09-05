<?php

namespace App\Http\Controllers;

use App\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(){
        $data = Alert::latest()->get();
        return view('alert.index', compact('data'));
    }
}
