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
        Schema::create("level_member", function(Blueprint $bp){
            $bp->id();
            $bp->string("level_name", 100)->nullable(false);
            $bp->unsignedInteger("level");
            $bp->enum("kategori", ['UMUM', 'MEMBER PRO']);
            $bp->text('description')->nullable();
            $bp->decimal('limit_transaction',14,2);
            $bp->boolean("publish")->default(true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("level_member");
    }
};
