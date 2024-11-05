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
        Schema::create("request_updates", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("user_id");
            $bp->text("json_temp");
            $bp->text("reason_user")->nullable();
            $bp->enum("status", ["Submited", "Rejected", "Approved"]);
            $bp->unsignedBigInteger("pengelola_user_id")->nullable();
            $bp->text("reason_admin")->nullable();
            $bp->timestamps();
            $bp->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("request_updates");
    }
};
