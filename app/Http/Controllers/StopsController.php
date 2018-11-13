<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use Illuminate\Http\Request;

class StopsController extends Controller
{
    public function index(Request $request)
    {  
        return response()->json(Stop::search(['name'])->limit(10)->get());
    }
}