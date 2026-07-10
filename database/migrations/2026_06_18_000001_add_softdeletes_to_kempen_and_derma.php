<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tambah SoftDeletes pada tbl_kempen dan tbl_derma.
 *
 * Tujuan:
 *  - Mengelakkan kehilangan data kewangan secara kekal (hard delete).
 *  - Rekod yang "dipadam" hanya akan ditanda dengan tarikh dalam lajur
 *    deleted_at, dan masih boleh dipulihkan (restore) atau diaudit.
 *  - Mematuhi amalan terbaik sistem kewangan — tiada data dipadam selama-lamanya.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Tambah lajur deleted_at pada tbl_kempen
        Schema::table('tbl_kempen', function (Blueprint $table) {
            $table->softDeletes(); // menambah lajur `deleted_at` nullable timestamp
        });

        // Tambah lajur deleted_at pada tbl_derma
        Schema::table('tbl_derma', function (Blueprint $table) {
            $table->softDeletes(); // menambah lajur `deleted_at` nullable timestamp
        });
    }

    public function down(): void
    {
        Schema::table('tbl_kempen', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tbl_derma', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
