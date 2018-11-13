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
    
    /**
     * Dynamic scope for performing search against specified columns in a model.
     *
     * @param   \Illuminate\Database\Eloquent\Builder $query
     * @param   array $columns
     * @param   string $phrase
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, array $columns, $phrase = null)
    {
        if (empty($phrase)) {
            $phrase = request()->get('phrase');
        }

        if (is_numeric($phrase) || filter_var($phrase, FILTER_VALIDATE_EMAIL)) {
            $words = [$phrase];
        } else {
            $words = str_word_count($phrase, 1);
        }

        foreach ($words as $word) {
            $word = trim($word);
            $operator = 'like';

            if (is_numeric($word)) {
                $operator = '=';
            } else {
                $word = "%$word%";
            }

            $query->where(function($builder) use ($columns, $operator, $word) {
                foreach ($columns as $column) {
                    $builder->orWhere($column, $operator, $word);
                }
            });
        }

        return $query;
    }
}