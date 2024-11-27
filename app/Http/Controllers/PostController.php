<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;//authentication


class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post,Category $category){
      $this->post = $post;
      $this->category =$category;
    }

    public function create(){
      $all_categories =$this->category->all();
      return view('users.posts.create')->with('all_categories',$all_categories);
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

  /**
   * This method (function) is use to retrieved details of post base on post id ($id)
   */
  public function show($post_id){
    $post = $this->post->findOrFail($post_id);
    //Same as: "SELECT * FROM posts WHERE id = $id";

    return view('users.posts.show')->with('post', $post);
  }

  /**
   * This is a method use to search for the post that we want to edit/update. The details of this post will displayed in the edit page.
   */
  public function edit($post_id){

    # Data 1
    $post = $this->post->findOrFail($post_id);
    //Same as: "SELECT * FROM posts WHERE id = $id";

    /**
     * If the AUTH USER is NOT the owner of the post, redirect to hompage.
     */
    if (Auth::user()->id != $post->user->id) {
      return redirect()->route('index');
    }

    /**
     * Retrieved all the category from categories table so we can display it in the edit page
     */

    # Data 2
    $all_categories = $this->category->all(); //Same as: "SELECT * FROM categories";

    # Get all the category ids of this post from category_post (PIVOT table) and saved in an array
    $selected_categories = [];
    foreach ($post->categoryPost as $category_post) {
      # Data 3
      $selected_categories[] = $category_post->category_id;
    }


    return view('users.posts.edit')
      ->with('post', $post)                                 //data 1
      ->with('all_categories', $all_categories)             //data 2
      ->with('selected_categories', $selected_categories);  //data3
  }

  /**
   * Method use to make/perform the actual updating of post data
   */
  public function update(Request $request, $post_id){
    #1. Validate the data from the form
    $request->validate([
      'category' => 'required|array|between:1,3',
      'description' => 'required|min:1|max:1000',
      'image' => 'mimes:jpeg,jpg,png,gif|max:1048'
    ]);

    #2. Update the post
    $post = $this->post->findOrFail($post_id);
    $post->description = $request->description; //new description from the form

    # check if the user uploaded a new image
    if ($request->image) {
      $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
    }
    $post->save();

    #3. Delete the old category ids, and save the new ones into category_post (PIVOT table)
    $post->categoryPost()->delete();
    //Use the relationship Post::categoryPost() to select the records related to a post
    //Same as: "DELETE FROM category_post WHERE post_id = $post_id";

    #4. Save the new categories to category_post table
    foreach ($request->category as $category_id) {
      $category_post[] = ['category_id' => $category_id];
    }
    $post->categoryPost()->createMany($category_post);

    #5. redirect to show post page (to confirm the update)
    return redirect()->route('post.show', $post_id);
  }

  public function destroy($post_id){  

      // $post = $this->findOrFail($post_id);
      // $post->categoryPost()->delete();

      $this->post->findOrFail($post_id)->forceDelete();
      return redirect()->route('index');
  }

}