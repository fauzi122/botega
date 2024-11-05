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
        Schema::create("product_images", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("product_id");
            $bp->string("name");
            $bp->string("path_file");
            $bp->text("description")->nullable();
            $bp->boolean("is_primary")->default(false)->nullable();
            $bp->timestamps();
            $bp->foreign("product_id")->on("products")
                ->references("id")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("product_images");
    }
};
