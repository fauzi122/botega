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
        Schema::create("article_comments", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("article_id");
            $bp->text("comment");
            $bp->unsignedBigInteger("parent_id")->default(0);
            $bp->unsignedBigInteger("likes")->default(0);
            $bp->unsignedBigInteger("dislikes")->default(0);
            $bp->unsignedBigInteger("users_id")->nullable();
            $bp->timestamps();
            $bp->foreign("users_id")->on("users")->references("id")
                ->cascadeOnUpdate();
            $bp->foreign("article_id")->on("articles")->references("id")
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("article_comments");
    }
};
