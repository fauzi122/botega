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
        Schema::create("gifts", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("user_id");
            $bp->unsignedBigInteger("gift_type_id")->nullable();
            $bp->unsignedBigInteger("pengelola_user_id")->nullable();
            $bp->dateTime("sent_at")->nullable();
            $bp->text("notes")->nullable();
            $bp->double("price")->unsigned()->default(0);
            $bp->dateTime("received_at")->nullable();
            $bp->timestamps();
            $bp->foreign("user_id")->on("users")->references("id");
            $bp->foreign("pengelola_user_id")->on("users")->references("id");
            $bp->foreign("gift_type_id")->on("gift_types")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("gifts");
    }
};
