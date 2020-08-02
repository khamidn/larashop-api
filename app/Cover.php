<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cover extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
    	'file_name'
    ];

    // public function books(){
    //     return $this->belongsToMany("App\Book");
    // }
}
