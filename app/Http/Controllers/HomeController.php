<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class HomeController extends Controller
{

    private $post;
    private $user;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
       $this->post = $post;
       $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $all_posts = $this->post->latest()->get();
        $home_posts = $this->getHomePosts(); //$home_posts[1,2]
        $suggested_users = $this->getSuggestedUsers();

        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users);
    }

    /**
     * Get all the posts of the users that the AUTH user is following
     */
    private function getHomePosts(){
        
        $all_posts = $this->post->latest()->get();
        $home_posts = [];


        /**
         * Posts table
         * post id      name of post     user_id
         *   1           Travel            Emiko (1)
         *   2           Music             Mark (2 )
         * 
         */
        foreach ($all_posts as $post) {
            if ($post->user->isFollowed() || $post->user->id === Auth::user()->id) {
                $home_posts[] = $post;
            }
        }
        return $home_posts; //In computer memory it look something like this $home_posts[1]
    }

    /**
     * Get all the users that the AUTH user is not yet following
     */
    private function getSuggestedUsers(){

        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach ($all_users as $user) {
            if( ! $user->isFollowed()){
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users, 0, 4);
        /**
         * array_slice(x, y,z)
         * x -> the array to slice
         * y -> starting index
         * z -> how many to display/output
         */

         /** */
    }

    /**
     * Search people
     */
    public function search(Request $request){
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->get();
        
        return view('users.search')
        ->with('users', $users)
        ->with('search', $request->search);
    }
}
