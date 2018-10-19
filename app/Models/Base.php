<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

abstract class Base extends Model
{
    const DEFAULT_STRING_LENGTH = 191;
    
    /**
     * Get unguarded attributes.
     * 
     * @return  array
     */
    public function getUnguarded()
    {
        $unguarded = [];
        
        if (!$this->totallyGuarded()) {
            $unguarded = arrray_diff(Schema::getColumnListing($this->getTable()), $this->getGuarded());
        }
        
        return $unguarded;
    }
}