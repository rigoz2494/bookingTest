<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $hidden = [
        'book_id'
    ];

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
