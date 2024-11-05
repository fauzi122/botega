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
        Schema::create("member_points", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("user_id");
            $bp->dateTime("received_at")->nullable();
            $bp->unsignedBigInteger("transaction_id")->nullable(true);
            $bp->unsignedBigInteger("points")->default(0);
            $bp->text('notes')->nullable();
            $bp->unsignedBigInteger("pengelola_user_id")->nullable();
            $bp->timestamps();
            $bp->foreign("user_id")->on("users")->references("id")
                ->cascadeOnUpdate();
            $bp->foreign("pengelola_user_id")->on("users")->references("id")
                ->cascadeOnUpdate();
            $bp->foreign("transaction_id")->on("transactions")->references("id")
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("member_points");
    }
};
