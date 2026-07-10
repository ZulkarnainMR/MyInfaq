<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_derma', function (Blueprint $table) {
            $table->id('id_resit');
            $table->unsignedBigInteger('id_kempen');
            $table->unsignedBigInteger('id_penderma')->nullable();
            $table->decimal('amaun_derma', 12, 2);
            $table->timestamp('tarikh_derma')->useCurrent();
            $table->enum('status_bayaran', ['Berjaya', 'Gagal', 'Pending'])->default('Pending');
            $table->string('no_resit')->unique()->nullable();
            $table->string('kaedah_bayaran')->default('Online');
            $table->text('nota')->nullable();
            $table->timestamps();

            $table->foreign('id_kempen')->references('id_kempen')->on('tbl_kempen')->onDelete('cascade');
            $table->foreign('id_penderma')->references('id_penderma')->on('tbl_penderma')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_derma');
    }
};
