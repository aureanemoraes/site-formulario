<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Oqf extends Model
{
    public $incrementing = false;
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('option_id', '=', $this->getAttribute('option_id'))
            ->where('question_id', '=', $this->getAttribute('question_id'))
            ->where('form_id', '=', $this->getAttribute('form_id'));
        return $query;
    }
}
