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
        Schema::create('tbl_kempen_updates', function (Blueprint $table) {
            $table->id('id_update');
            $table->unsignedBigInteger('id_kempen');
            $table->string('tajuk_update');
            $table->text('keterangan_update');
            $table->string('gambar_update')->nullable();
            $table->timestamps();

            $table->foreign('id_kempen')->references('id_kempen')->on('tbl_kempen')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_kempen_updates');
    }
};
