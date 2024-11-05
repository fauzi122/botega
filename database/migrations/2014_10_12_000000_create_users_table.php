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
//        Schema::create('usersx', function (Blueprint $table) {
//            $table->id();
//            $table->string('username');
//            $table->string('fullname');
//            $table->string('email')->unique();
//            $table->string('email_verified_at')->nullable();
//            $table->string('nohp')->nullable();
//            $table->string('status')->nullable();
//            $table->string('password');
//            $table->string('level')->default('user'); // contoh kolom level
//            $table->string('reset_token')->nullable(); // contoh kolom reset token
//            $table->string('foto')->nullable(); // contoh kolom foto
//            $table->rememberToken();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::dropIfExists('usersx');
    }
};
