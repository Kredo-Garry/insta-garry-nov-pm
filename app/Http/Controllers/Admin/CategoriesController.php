<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;

class CategoriesController extends Controller
{
    private $category;
    private $post;

    public function __construct(Category $category, Post $post){
        $this->category = $category;
        $this->post = $post;
    }

    /**
     * Get all the lists of categories from categories table
     */
    public function index(){

        ### Activity Guide ###
        # 1. Initialized an empty property/variable to hold the count of uncategorized post (uncategorized_post = 0)
        $uncategorized_post = 0;

        # 2. Get all the posts from posts table
        $all_posts = $this->post->all(); //"SELECT * FROM posts";

        # 3. Loop over all the posts, and use the categoryPost() eloquent method relationship to check if the posts have categories or without category
        foreach ($all_posts as $post) {
            if ($post->categoryPost->count() == 0) {
                 # 4. if the post don't have category increment the uncategorized property (e.g. : uncategorized++ )
                 $uncategorized_post++; //add 1 to the property
            }
        }

        $all_categories = $this->category->orderBy('updated_at', 'desc')->paginate(10);
        return view('admin.categories.index')
            ->with('all_categories', $all_categories)
            ->with('uncategorized_post', $uncategorized_post);
    }

    /**
     * Method to insert new category
     */
    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:1|max:50|unique:categories,name'
        ]);

        $this->category->name = ucwords(strtolower($request->name));
        $this->category->save();
        
        return redirect()->back();
    }

    /**
     * Method to update the category
     */
    public function update(Request $request, $category_id){
        $request->validate([
            'new_name' => 'required|min:1|max:50|unique:categories,name,' . $category_id
        ]);

        $category = $this->category->findOrFail($category_id);
        $category->name = ucwords(strtolower($request->new_name));
        $category->save();

        return redirect()->back();
    }

    /**
     * Method to delete category
     */
    public function destroy($category_id){
        $this->category->destroy($category_id);
        return redirect()->back();
    }
}
