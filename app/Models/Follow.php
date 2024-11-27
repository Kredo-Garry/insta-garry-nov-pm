<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    public $timestamps = false;

    /**
     * Use this method to get the info of a follower
     */
    public function follower(){
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
        /**
         *   * Follows table
         * -------------
         * follower_id         following_id
         *     1                    3
         *     2                    3
         */
    }


    /**
     * Use this method to get the info of the user being followed
     */
    public function following(){
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
    }
}
