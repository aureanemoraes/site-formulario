<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Aqf extends Model
{
    public $incrementing = false;
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query
            ->where('Customer_No', '=', $this->getAttribute('Customer_No'))
            ->where('Address_Name', '=', $this->getAttribute('Address_Name'));
        return $query;
}
