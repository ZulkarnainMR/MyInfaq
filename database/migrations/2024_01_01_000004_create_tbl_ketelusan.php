<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_ketelusan', function (Blueprint $table) {
            $table->id('id_ketelusan');
            $table->unsignedBigInteger('id_kempen');
            $table->string('tajuk_laporan')->nullable();
            $table->text('keterangan_penerima');
            $table->json('gambar_agihan')->nullable()->comment('JSON array of image paths');
            $table->date('tarikh_agihan');
            $table->integer('bilangan_penerima')->default(0);
            $table->enum('status_audit', ['Pending', 'Diluluskan', 'Ditolak'])->default('Pending');
            $table->unsignedBigInteger('id_staf')->nullable();
            $table->timestamp('tarikh_audit')->nullable();
            $table->text('nota_audit')->nullable();
            $table->timestamps();

            $table->foreign('id_kempen')->references('id_kempen')->on('tbl_kempen')->onDelete('cascade');
            $table->foreign('id_staf')->references('id_staf')->on('tbl_staf')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_ketelusan');
    }
};
