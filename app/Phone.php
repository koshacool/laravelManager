<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function phoneTypes()
    {
        return $this->hasMany('App\PhoneType');
    }
}
