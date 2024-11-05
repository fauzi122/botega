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
        Schema::create('detail_retur_penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_accurate");
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("retur_id");
            $table->string("retur_no", 20)->nullable(true);
            $table->string("so_number", 20)->nullable(true);
            $table->double("qty")->default(0);
            $table->double("dpp_amount")->default(0);
            $table->double("return_amount")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_retur_penjualans');
    }
};
