<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	protected $table = 'contact';
    protected $fillable = [
    	'email','phone', 'address', 'city_id', 'province_id', 'postal_code'
    ];
}
