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
        Schema::create('proses_history', function (Blueprint $table) {
            $table->id();
            $table->string('approval_status', 40);
            $table->date('history_date');
            $table->string('history_number', 50);
            $table->string('history_type', 10);
            $table->double('history_amount');
            $table->unsignedBigInteger('transactions_id');
            $table->unsignedBigInteger('id_accurate');
            $table->foreign('transactions_id')->on('transactions')->references('id')->cascadeOnDelete()
                    ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proses_history');
    }
};
