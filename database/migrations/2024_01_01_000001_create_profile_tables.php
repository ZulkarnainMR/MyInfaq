<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Profile table for Donors
        Schema::create('tbl_penderma', function (Blueprint $table) {
            $table->id('id_penderma');
            $table->unsignedBigInteger('id_user')->unique();
            $table->string('nama_penderma');
            $table->string('no_telefon')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tbl_users')->onDelete('cascade');
        });

        // Profile table for Organizations
        Schema::create('tbl_organisasi', function (Blueprint $table) {
            $table->id('id_organisasi');
            $table->unsignedBigInteger('id_user')->unique();
            $table->string('nama_organisasi');
            $table->string('no_pendaftaran')->unique();
            $table->string('no_telefon');
            $table->string('alamat')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tbl_users')->onDelete('cascade');
        });

        // Profile table for Staff
        Schema::create('tbl_staf', function (Blueprint $table) {
            $table->id('id_staf');
            $table->unsignedBigInteger('id_user')->unique();
            $table->string('nama_staf');
            $table->string('jawatan')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('tbl_users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_staf');
        Schema::dropIfExists('tbl_organisasi');
        Schema::dropIfExists('tbl_penderma');
    }
};
