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
        Schema::create("role_access_rights", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("role_id");
            $bp->unsignedBigInteger("access_right_id");
            $bp->boolean("grant")->default(false);
            $bp->timestamps();
            $bp->foreign("role_id")->on("roles")->references("id")->cascadeOnUpdate();
            $bp->foreign("access_right_id")->on("access_rights")->references("id")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("role_access_rights");
    }
};
