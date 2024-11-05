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
        Schema::create("logs", function(Blueprint $bp){
            $bp->id();
            $bp->string("actions");
            $bp->text("payload")->nullable();
            $bp->unsignedBigInteger("user_id")->nullable();
            $bp->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("logs");
    }
};
