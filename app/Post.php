<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $fillable = [
        'user_id', 'category_id', 'name', 'slug', 'excerpt', 'body', 'status', 'file'
    ];

    //Relacion con el modelo User
    public function user() {

        return $this->belongsTo(User::class);

    }

    //Relacion con el modelo Category
    public function category() {

        return $this->belongsTo(Category::class);

    }

    //Relacion con el modelo Tag
    public function tags() {

        return $this->belongsToMany(Tag::class);

    }


}
