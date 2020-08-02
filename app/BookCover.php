<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookCover extends Model
{
    protected $table ="book_cover";

    protected $fillable = [
    	'book_id', 'cover_id'
    ];
}
