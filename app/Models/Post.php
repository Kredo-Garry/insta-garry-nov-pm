<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    /**
     * A post belongs to a user
     * Use this method to get the owner of the post
     */
    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * To get the categories under a post
     * Use this method to get the categories of the post
     */
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }

    /**
     * Use this method to get all the comments of a post
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * Use this method to get all the likes of the post
     */
    public function likes(){
        return $this->hasMany(Like::class);
    }

    /**
     * Check if the post is already liked by the user.
     * This method will return TRUE if the user already liked the post.
     */
    public function isLiked(){
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
