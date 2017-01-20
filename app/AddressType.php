<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddressType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
    ];

    public function address()
    {
        return $this->belongsTo('App\Address');
    }
}
