<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = [
        'name', 'slug', 'body'
    ];

    //Relacion con el modelo Post
    public function posts() {

        return $this->hasMany(Post::class);

    }

}
