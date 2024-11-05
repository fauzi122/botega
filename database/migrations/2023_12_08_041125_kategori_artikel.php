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
        Schema::create("article_categories", function(Blueprint $bp){
            $bp->id();
            $bp->string("category", 100)->nullable(false);
            $bp->boolean("publish")->default(true);
            $bp->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("article_categories");
    }
};
