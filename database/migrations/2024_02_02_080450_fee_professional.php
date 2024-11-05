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
        Schema::create('fee_professional', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_number_id');
            $table->unsignedBigInteger('member_user_id');
            $table->unsignedBigInteger('transaction_id');
            $table->string('periode',7);
            $table->unsignedBigInteger('proses_history_invoice_id')->nullable();
            $table->date('invoice_date')->nullable();
            $table->unsignedBigInteger('proses_history_nomor_sj')->nullable();
            $table->unsignedBigInteger('detail_transaction_id');
            $table->unsignedInteger('num_split')->nullable();
            $table->unsignedBigInteger('detail_delivery_id')->nullable();
            $table->double('pqty')->nullable();
            $table->string('unit', 50)->nullable();
            $table->double('dpp_amount')->nullable();
            $table->double('discount')->nullable();
            $table->double('total_price')->nullable();
            $table->double('fee_percent')->nullable();
            $table->double('fee_amount')->nullable();
            $table->boolean('npwp')->default(false);
            $table->double('pph_percent')->default(2.5);
            $table->double('pph_amount')->default(0);
            $table->double('percentage_fee')->default(0);
            $table->double('total_pembayaran')->default(0);
            $table->double('total_tagihan')->default(0);
            $table->double('harus_dibayar')->default(0);
            $table->unsignedBigInteger('admin_user_id');
            $table->dateTime('dt_pengajuan')->nullable();
            $table->dateTime('dt_proses')->nullable();
            $table->dateTime('dt_acc')->nullable();
            $table->dateTime('dt_finish')->nullable();
            $table->string('nama_bank',100)->nullable();
            $table->string('bank_kota',100)->nullable();
            $table->string('no_rekening',100)->nullable();
            $table->string('tag',100)->nullable();
            $table->string('an_rekening',150)->nullable();
            $table->string('retur_no',25)->nullable();
            $table->double('retur_qty')->nullable();
            $table->string('no_faktur', 100)->nullable();
            $table->timestamps();
            $table->foreign('admin_user_id')->on('users')->references('id')
                    ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['member_user_id', 'transaction_id', 'periode']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_professional');
    }
};
