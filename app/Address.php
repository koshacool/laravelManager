<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'contact_id', 'address_type'
    ];


    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function addressTypes()
    {
        return $this->hasMany('App\AddressType');
    }
}
