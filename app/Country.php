<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function location()
    {
        return $this->belongsTo('App\Location');
    }
}
