<?php

use Illuminate\Support\Facades\Route;
/**
 * Controller for admin users
 */
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\CategoriesController;


/**
 * Controller use for regular users
 */
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\FollowController;

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [HomeController::class, 'index'])->name('index'); //home.blade.php (homepage)
    Route::get('/people', [HomeController::class, 'search'])->name('search');

    /**
     * Routes related to posts
     */
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');

    /**
     * Route to store the post details coming from the form
     */
    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');

    /**
     * Route to open show post page
     */
    Route::get('/post/{post_id}/show', [PostController::class, 'show'])->name('post.show');

    /**
     * Route use to open the edit page, and display the details of the post to edit/update
     */
    Route::get('/post/{post_id}/edit', [PostController::class, 'edit'])->name('post.edit');

    /**
     * Route use to perform the actual action of making the update
     */
    Route::patch('/post/{post_id}/update', [PostController::class, 'update'])->name('post.update');

    /**
     * Route use to destroy/delete a post
     */
    Route::delete('/post/{post_id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    /**
     * Routes related to comments
     */
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');

    /**
     * Route use to delete comment
     */
    Route::delete('/comment/{comment_id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');

    /**
     * Routes related to Users Profile
     */
    Route::get('/profile/{user_id}/show',[ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user_id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{user_id}/following', [ProfileController::class, 'following'])->name('profile.following');

    /**
     * Routes related to likes
     */
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/like/{post_id}/destroy', [LikeController::class, 'destroy'])->name('like.destroy');

    /**
     * Routes related to Follow/Unfollow users
     */

     # Follow
     Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');
     # Unfollow
     Route::delete('/follow/{user_id}/destroy', [FollowController::class, 'destroy'])->name('follow.destroy');

     /**
      * Routes related to admin user
      */
      Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
        # Admin User
        Route::get('/users', [UsersController::class, 'index'])->name('users'); //admin.users
        Route::delete('/users/{user_id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate'); //admin.users.deactivate
        Route::patch('/users/{user_id}/activate', [UsersController::class, 'activate'])->name('users.activate');

        # Admin: Post
        Route::get('/posts', [PostsController::class, 'index'])->name('posts');
        Route::delete('/posts/{post_id}/hide', [PostsController::class, 'hide'])->name('posts.hide');
        Route::patch('/post/{post_id}/unhide', [PostsController::class, 'unhide'])->name('posts.unhide');

        # Admin: Category
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories'); //admin.categories
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store');
        Route::patch('/categories/{category_id}/update', [CategoriesController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category_id}/destroy', [CategoriesController::class, 'destroy'])->name('categories.destroy');
      });
});

