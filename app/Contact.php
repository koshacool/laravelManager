<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first', 'last', 'email', 'birthday', 'user_id','id'
    ];

    public function setFirstAttribute($value)
    {
        $this->attributes['first'] = ucfirst($value);
    }

    public function setLastAttribute($value)
    {
        $this->attributes['last'] = ucfirst($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim($value);
    }



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
