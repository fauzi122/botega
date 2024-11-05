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
        Schema::create("articles", function(Blueprint $bp){
            $bp->id();
            $bp->string("judul", 200)->nullable(false);
            $bp->string("keyword", 200)->nullable();
            $bp->text("article");
            $bp->dateTime("published_at")->nullable();
            $bp->dateTime("expired_at")->nullable();
            $bp->unsignedBigInteger("article_category_id")->nullable();
            $bp->unsignedBigInteger("product_id")->nullable();
            $bp->unsignedBigInteger("pengelola_user_id");
            $bp->unsignedInteger("hit")->default(0);
            $bp->unsignedInteger("comment_count")->default(0);
            $bp->unsignedInteger("likes")->default(0);
            $bp->unsignedInteger("dislikes")->default(0);
            $bp->string("path_images", 255)->nullable();
            $bp->timestamps();

            $bp->foreign("article_category_id")->on("article_categories")
                ->references("id")->cascadeOnUpdate();
            $bp->foreign("product_id")->on("products")
                ->references("id")->cascadeOnUpdate();
            $bp->foreign("pengelola_user_id")->on("users")
                ->references("id")->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("articles");
    }
};
