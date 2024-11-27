<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Comment; //represents comments table

class CommentController extends Controller
{
    # define property
    private $comment;

    # define constructor
    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    /**
     * Use this method to store the comment details into the comments table
     */
    public function store(Request $request, $post_id){
        #1. Validate the data coming from the form before it reach database
        $request->validate(
            [
                'comment_body' . $post_id => 'required|max:150'
            ],
            [
                'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.',
                'comment_body' . $post_id . '.max' => 'The comment must not have more than 150 characters.'
            ]
        );

        #2 Save the comment
        /**
         * The reason why we have the " 'comment_body' . $post_id " it is because we want to display all the comments of the specific post.
         * 
         * We have to specify which post is the comment for.
         */
        $this->comment->body = $request->input('comment_body' . $post_id); //the actual comments
        $this->comment->user_id = Auth::user()->id; // the owner of the comments
        $this->comment->post_id = $post_id;        // the post being commented on
        $this->comment->save();                  //save the comment datails to database 

        #3. redirection
        return redirect()->route('post.show', $post_id); //redirect to show post page
    }

    /**
     * Method use to delete/destroy the comment
     */
    public function destroy($comment_id){
        $this->comment->destroy($comment_id);
        return redirect()->back();
    }

}
