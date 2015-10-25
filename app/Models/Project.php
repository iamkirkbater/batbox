<?php

namespace Batbox\Models;

use \Eloquent as Model;

class Project extends Model
{
    protected $fillable = ['*'];


    public function getLastUpdatedAttribute()
    {
        $date = new \DateTime($this->updated_at);

        return $date->format('l, d-M-y H:i:s');
    }
}
