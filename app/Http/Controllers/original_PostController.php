<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; //authentication
use Illuminate\Http\Request;
use App\Models\Post;     // posts table
use App\Models\Category; // categories table

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category){
        $this->post = $post;
        $this->category = $category;
    }

    public function create(){
        // Retrieved or get all the categories from the categories table
        $all_categories = $this->category->all();
        # Same: "SELECT * FROM posts";

        return view('users.posts.create')->with('all_categories', $all_categories);

        /**
         * $all_categories [
         *   1 => 'Travel,
         *   2 => 'Food',
         *   3 => 'Lifestyle',
         *   4 => 'Technology',
         *   ....
         * ]
         */
    }

    public function store(Request $request){
        #1. Validate the from the form
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpeg,jpg,png,gif,max:1048'
        ]);

        #2. Save the post details
        $this->post->user_id  = Auth::user()->id; //the owner of the post
        $this->post->image    = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description = $request->description;
        $this->post->save(); // post id 1


        #3. Save the categories to the category_post table
        /** $request->category[1,5,6] */
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
            /**
             * $category_post[1,5,6]
             */
        }
        $this->post->categoryPost()->createMany( $category_post);
        # post id 1                 $category_post[1,5,6]
        #  $category_post[
        #   ['post_id' => 1, 'category_id' => 1],
        #   ['post_id' => 1, 'category_id' => 5],
        #   ['post_id' => 1, 'category_id' => 6],
        # ]

        #4. Go back to homepage
        return redirect()->route('index');
    }
}
