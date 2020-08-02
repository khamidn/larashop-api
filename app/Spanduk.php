<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spanduk extends Model
{
	use SoftDeletes;
	protected $date = ['deleted_at'];
	protected $table = 'spanduks';
	protected $fillable = [
		'name', 'image_spanduk', 'creator', 'category', 'status'
	];

    
}
