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
        Schema::create("gift_types", function(Blueprint $bp){
            $bp->id();
            $bp->string("name", 255);
            $bp->decimal("price", 10, 2)->default(0);
            $bp->text("description")->nullable();
            $bp->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("gift_types");
    }
};
