<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;

class SchedulesController extends Controller
{
    public function closest($id)
    {
        $schedules = Schedule::query()
            ->with(['vehicle', 'route'])
            ->where('stop_id', $id)
            ->where('day', date('w'))
            ->whereBetween('time', [
                (new Carbon())->toTimeString(), 
                (new Carbon())->addMinute(10)->toTimeString()
            ])
            ->limit(5)
            ->get();
        
        return response()->json($schedules);
    }
}