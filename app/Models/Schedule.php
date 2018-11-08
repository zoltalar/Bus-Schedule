<?php

namespace App\Models;

class Schedule extends Base
{
    protected $table = 'schedules';
    
    protected $guarded = ['id'];
    
    public $timestamps = false;
}