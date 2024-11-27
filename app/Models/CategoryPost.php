<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'category_post';

    //we will use crateMany() method --- this will accept array of data
    protected $fillable = ['post_id', 'category_id']; 
    public $timestamps = false; // we don't want to use timestamps

    /**
     * To get the name of the category
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
