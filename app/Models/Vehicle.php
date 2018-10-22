<?php

namespace App\Models;

final class Vehicle extends Base
{
    protected $table = 'vehicles';
    
    protected $guarded = ['id'];
}