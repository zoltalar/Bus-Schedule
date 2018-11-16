<?php

namespace App\Models;

class Schedule extends Base
{
    protected $table = 'schedules';
    
    protected $guarded = ['id'];
    
    public $timestamps = false;
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}