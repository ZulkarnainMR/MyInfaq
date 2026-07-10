<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_kempen', function (Blueprint $table) {
            $table->id('id_kempen');
            $table->unsignedBigInteger('id_organisasi');
            $table->string('tajuk_kempen');
            $table->text('keterangan_kempen');
            $table->decimal('sasaran_dana', 12, 2);
            $table->decimal('jumlah_kutipan_semasa', 12, 2)->default(0.00);
            $table->enum('status_kempen', ['Pending', 'Aktif', 'Ditolak', 'Selesai', 'Dibayar'])->default('Pending');
            $table->string('gambar_kempen')->nullable();
            $table->date('tarikh_tamat')->nullable();
            $table->unsignedBigInteger('id_staf')->nullable()->comment('Staff who verified the campaign');
            $table->timestamp('tarikh_semakan')->nullable();
            $table->text('sebab_tolak')->nullable();
            $table->boolean('bayaran_diminta')->default(false);
            $table->timestamp('tarikh_minta_bayaran')->nullable();
            $table->boolean('bayaran_diluluskan')->default(false);
            $table->timestamp('tarikh_bayaran_diluluskan')->nullable();
            $table->timestamps();

            $table->foreign('id_organisasi')->references('id_organisasi')->on('tbl_organisasi')->onDelete('cascade');
            $table->foreign('id_staf')->references('id_staf')->on('tbl_staf')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_kempen');
    }
};
