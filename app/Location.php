<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id', 'country_id', 'state_id', 'contact_id'
    ];

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function city()
    {
        return $this->hasOne('App\City');
    }

    public function state()
    {
        return $this->hasOne('App\State');
    }

    public function country()
    {
        return $this->hasOne('App\Country');
    }
}
