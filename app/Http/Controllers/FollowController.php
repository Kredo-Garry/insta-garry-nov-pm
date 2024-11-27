<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Follow;

class FollowController extends Controller
{
    private $follow;

    public function __construct(Follow $follow){
        $this->follow = $follow;
    }

    /**
     * This is method is use to save/store the follow user
     */
    public function store($user_id){
        $this->follow->follower_id = Auth::user()->id; //the follower
        $this->follow->following_id = $user_id; //the user being followed
        $this->follow->save();
        //Same as: "INSERT INTO follows(follower_id, following_id) VALUES('Auth::user()->id', '$user_id')";

        return redirect()->back();
    }

    /**
     * This method will destroy/unfollow a user
     */
    public function destroy($user_id){
        $this->follow
            ->where('follower_id', Auth::user()->id)
            ->where('following_id', $user_id)
            ->delete();

        return redirect()->back();
    }
}
