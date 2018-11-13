<?php

namespace App\Http\Controllers;

use App\Models\Stop;

class StopsController extends Controller
{
    public function index()
    {
        return response()->json(Stop::limit(10)->get());
    }
}