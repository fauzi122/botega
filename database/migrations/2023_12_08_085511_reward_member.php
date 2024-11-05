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
        Schema::create("member_rewards", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("user_id")->nullable();
            $bp->unsignedBigInteger("point")->default(0);
            $bp->unsignedBigInteger("reward_id")->nullable();
            $bp->unsignedBigInteger("pengelola_user_id")->nullable();
            $bp->dateTime("approved_at")->nullable();
            $bp->text("notes")->nullable();
            $bp->smallInteger("status")->default(0)->nullable();
            $bp->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("member_rewards");
    }
};
