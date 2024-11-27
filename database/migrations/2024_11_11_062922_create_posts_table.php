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
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); //AUTO_INCREMENT -- the id of the post
            $table->text('description'); //1000 characters --the description of the post
            $table->longText('image'); // the image of the post
            $table->unsignedBigInteger('user_id'); // the owner of the post
            $table->timestamps();//the date and time the post is created

            //link the foreign key (user_id) of the posts table to the primary key (id) of users table
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
