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
        Schema::create('detail_item_surat_jalan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_transaction_id');
            $table->string('number', 100)->nullable();
            $table->double('process_qty')->unsigned()->default(0);
            $table->string('unit', 100)->nullable();
            $table->string('number_sj', 100)->nullable();
            $table->string('number_in', 100)->nullable();
            $table->string('number_so', 100)->nullable();
            $table->unsignedBigInteger('fee_professional_id')->nullable();
            $table->unsignedBigInteger('proses_history_id')->nullable();
            $table->unsignedBigInteger('id_accurate_no_sj');
            $table->unsignedBigInteger('id_accurate');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_item_surat_jalan');
    }
};
