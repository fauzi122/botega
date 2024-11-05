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
        Schema::create("wilayah", function(Blueprint $bp){
            $bp->id();
            $bp->string("kode", 20);
            $bp->string("nama", 200);
            $bp->enum("tingkat", ["PROV", "KABKO", "KEC", "KEL"]);
            $bp->unsignedInteger("parent_id")->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("wilayah");
    }
};
