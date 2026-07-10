<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_testimoni', function (Blueprint $table) {
            $table->id('id_testimoni');
            $table->string('nama');
            $table->enum('peranan', ['Penderma', 'Organisasi', 'Awam'])->default('Penderma');
            $table->integer('bintang');
            $table->text('ulasan');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_testimoni');
    }
};
