<?php

namespace App\Extensions;

class Str extends \Illuminate\Support\Str
{
    public static function isHtml($value) 
    {
        return $value != strip_tags($value);
    }
}