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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('body');   //the actual comments
            $table->unsignedBigInteger('user_id'); //the user/owner of the comment
            $table->unsignedBigInteger('post_id'); //the post being commented on
            $table->timestamps();                  //date and time of comment

            $table->foreign('user_id')->references('id')->on('users'); //connect to users table
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade'); //connect to posts table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
