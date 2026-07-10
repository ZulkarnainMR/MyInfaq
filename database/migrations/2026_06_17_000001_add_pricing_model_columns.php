<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_organisasi', function (Blueprint $table) {
            $table->enum('payment_status', ['Pending', 'Paid'])->default('Pending')->after('alamat');
            $table->string('activation_bill_code')->nullable()->after('payment_status');
        });

        Schema::table('tbl_derma', function (Blueprint $table) {
            $table->decimal('platform_tip', 12, 2)->default(0.00)->after('amaun_derma');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_organisasi', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'activation_bill_code']);
        });

        Schema::table('tbl_derma', function (Blueprint $table) {
            $table->dropColumn('platform_tip');
        });
    }
};
