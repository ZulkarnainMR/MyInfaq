<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_derma', function (Blueprint $table) {
            $table->string('bill_code')->nullable()->after('kaedah_bayaran');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_derma', function (Blueprint $table) {
            $table->dropColumn('bill_code');
        });
    }
};
