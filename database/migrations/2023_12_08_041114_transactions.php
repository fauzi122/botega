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
        Schema::create("transactions", function(Blueprint $bp){
            $bp->id();
            $bp->date("trx_at");
            $bp->string("invoice_no");
            $bp->unsignedBigInteger("member_user_id")->nullable(false);
            $bp->unsignedBigInteger("pengelola_user_id")->nullable();
            $bp->decimal("total",10,2)->default(0);
            $bp->decimal("total",10,2)->default(0);
            $bp->text("notes")->nullable();
            $bp->string("nomor_so", 20)->nullable();
            $bp->string("nomor_sj", 20)->nullable();
            $bp->unsignedInteger("point")->nullable();
            $bp->timestamps();
            $bp->foreign("member_user_id")->on("users")->references("id")
                    ->cascadeOnUpdate();
            $bp->foreign("pengelola_user_id")->on("users")->references("id")
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("transactions");
    }
};
