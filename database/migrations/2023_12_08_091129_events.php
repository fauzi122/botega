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
        Schema::create("events", function (Blueprint $bp){
            $bp->id();
            $bp->date("start");
            $bp->date("end")->nullable();
            $bp->text("descriptions")->nullable();
            $bp->boolean("publish")->default(false);
            $bp->string("judul", 100)->nullable();
            $bp->unsignedBigInteger("user_id")->nullable();
            $bp->timestamps();
            $bp->foreign("user_id")->on("users")
                ->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("events");
    }
};
