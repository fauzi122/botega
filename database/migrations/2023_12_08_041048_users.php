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
        Schema::create("users", function(Blueprint $bp){
            $bp->id();
            $bp->string("id_no", 80)->nullable(false);
            $bp->string("first_name", 80)->nullable(false);
            $bp->string("last_name", 90)->nullable(true);
            $bp->enum("sub_kategori", ["ARSITEK","KONTRAKTOR",'DESAINER', 'UMUM', 'MEMBER PRO'])->default("UMUM");
            $bp->enum("user_type", ["admin","member"])->default("member");
            $bp->enum("gender", ["L","P"]);
            $bp->date("birth_at");
            $bp->string("npwp", 80)->nullable();
            $bp->string("nik", 80)->nullable();
            $bp->string("nppkp", 80)->nullable();
            $bp->string("home_addr", 255)->nullable();
            $bp->string("country", 255)->nullable();
            $bp->string("rt", 5)->nullable();
            $bp->string("rw", 5)->nullable();
            $bp->string("zip_code", 16)->nullable();
            $bp->string("phone", 16)->nullable();
            $bp->string("hp", 16)->nullable();
            $bp->string("wa", 16)->nullable();
            $bp->string("email", 255)->nullable();
            $bp->string("fax", 255)->nullable();
            $bp->string("web", 255)->nullable();
            $bp->string("pass", 255)->nullable();
            $bp->string("token_reset", 255)->nullable();
            $bp->string("foto_path", 255)->nullable();
            $bp->string("code_verify_email", 255)->nullable();
            $bp->dateTime("code_verify_email_expire")->nullable();
            $bp->string("code_verify_nohp", 255)->nullable();
            $bp->dateTime("code_verify_nohp_expire")->nullable();
            $bp->dateTime("date_verify_email")->nullable();
            $bp->dateTime("date_verify_nohp")->nullable();
            $bp->unsignedBigInteger("role_id")->nullable(true);
            $bp->unsignedBigInteger("level_member_id")->nullable(true);
            $bp->unsignedBigInteger("prov_id")->nullable(true);
            $bp->unsignedBigInteger("kabko_id")->nullable(true);
            $bp->unsignedBigInteger("kec_id")->nullable(true);
            $bp->unsignedBigInteger("kel_id")->nullable(true);
            $bp->unsignedBigInteger("cabang_id")->nullable(true);
            $bp->unsignedDouble("points")->default(0);
            $bp->unsignedDouble("fee")->default(0);
            $bp->unsignedDouble("total_spents")->default(0);
            $bp->foreign("level_member_id")->on("level_member")->references("id")
                    ->cascadeOnUpdate();
            $bp->foreign("cabang_id")->on("cabang")->references("id")
                ->cascadeOnUpdate();

            $bp->timestamps();
            $bp->unique('id_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("users");
    }
};
