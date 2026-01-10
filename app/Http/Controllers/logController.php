<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Log;

class logController extends Controller
{
    public function index(){
        $log = Log::latest()->get();
        return view('control.log.index',compact('log'));
    }
}
