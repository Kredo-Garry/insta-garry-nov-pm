<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_post', function (Blueprint $table) {
           $table->unsignedBigInteger('post_id'); //the id of the post
           $table->unsignedBigInteger('category_id'); // the category ids of the post
            

           /**
            * Note: onDelete()
            * When a post is deleted by the owner of the posts, the related record of that posts in PIVOT table will also be deleted because of the onDelete(cascade) method.
            */
           $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
           $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_post');
    }
};
