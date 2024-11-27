<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    # User::ADMIN_ROLE_ID
    const ADMIN_ROLE_ID = 1; // administrator
    const USER_ROLE_ID = 2; //  regular users

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Use this method to get all the posts of the user
     */
    public function posts(){
        return $this->hasMany(Post::class)->latest(); //latest() is use to sort the result of this query in descending order (the latest post will be on top)
    }

    /**
     * Use this method to get all the followers of the user
     */
    public function followers(){
        return $this->hasMany(Follow::class, 'following_id');
        /**
         * Users table
         * ---------
         * 1   John
         * 2   Mark
         * 3   Tim
         * 
         * 
         * Follows table
         * -------------
         * follower_id         following_id
         *     1                    3
         *     2                    3
         * 
         */
    }

    /**
     * Use this method to get the users that the user is following
     */
    public function following(){
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function isFollowed(){
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        /**
         * Auth::user()->id is the follower
         * Firstly, get all the followers of the User ( $this->followers() ). Then from the list,
         * search for the Auth user from the follower column ( where('follower_id', Auth::user()->id) )
         */
    }
}
