<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'best_phone', 'contact_id','phone_type',
    ];

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function phoneTypes()
    {
        return $this->hasMany('App\PhoneType');
    }
}
