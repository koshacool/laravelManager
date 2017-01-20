<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function phones()
    {
        return $this->hasMany('App\Phone');
    }

    public function location()
    {
        return $this->hasOne('App\Location');
    }

}
