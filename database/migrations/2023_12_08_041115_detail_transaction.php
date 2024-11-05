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
        Schema::create("detail_transactions", function(Blueprint $bp){
            $bp->id();
            $bp->unsignedBigInteger("id_accurate")->nullable(false);
            $bp->unsignedBigInteger("transaction_id")->nullable(false);
            $bp->unsignedBigInteger("product_id")->nullable(false);
            $bp->unsignedDouble("qty")->default(0);
            $bp->string("unit", 100)->nullable();
            $bp->string("retur_no",25)->nullable(true);
            $bp->unsignedDouble("retur_qty")->default(0);
            $bp->text("notes")->nullable();
            $bp->decimal("cost_price",13,2)->default(0);
            $bp->decimal("sale_price",13,2)->default(0);
            $bp->decimal("discount",10,2)->default(0);
            $bp->decimal("ppn",10,2)->default(0);
            $bp->unsignedBigInteger('user_id')->nullable(true);
            $bp->unsignedDouble("dpp_amount")->default(0);
            $bp->unsignedDouble("total_price")->default(0);
            $bp->string('salesname', 120)->nullable();
            $bp->string('item_disc_percent', 120)->nullable();
            $bp->string('status_claim', 120)->nullable();
            $bp->timestamps();
            $bp->foreign('user_id')->on('users')->references('id')
                    ->cascadeOnUpdate();
            $bp->foreign('transaction_id')
                ->on('transactions')->references('id')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("detail_transactions");
    }
};
