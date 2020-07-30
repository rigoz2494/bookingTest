<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * @SWG\Definition(
     *  definition="Book",
     *  @SWG\Property(
     *      property="id",
     *      type="integer"
     *  ),
     *  @SWG\Property(
     *      property="title",
     *      type="string"
     *  ),
     *  @SWG\Property(
     *      property="description",
     *      type="text"
     *  )
     * )
     */

    protected $fillable = [
        'title',
        'description',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
