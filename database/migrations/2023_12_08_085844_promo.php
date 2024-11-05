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
        Schema::create("promo", function (Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("level_member_id")->nullable();
            $bp->unsignedBigInteger("product_id")->nullable();
            $bp->unsignedDecimal("price", 10,2)->default(0);
            $bp->dateTime("expired_at")->nullable();
            $bp->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("promo");
    }
};
