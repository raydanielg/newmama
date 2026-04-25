<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->decimal('subtotal', 18, 2)->nullable()->after('description');
            $table->decimal('vat_amount', 18, 2)->nullable()->after('subtotal');
            $table->string('payment_terms')->nullable()->after('due_date');
        });

        Schema::table('voucher_lines', function (Blueprint $table) {
            $table->decimal('unit_price', 15, 2)->nullable()->after('unit_cost');
            $table->decimal('discount_pct', 8, 2)->nullable()->after('unit_price');
            $table->decimal('vat_amount', 18, 2)->nullable()->after('discount_pct');
        });
    }

    public function down(): void
    {
        Schema::table('voucher_lines', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'discount_pct', 'vat_amount']);
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat_amount', 'payment_terms']);
        });
    }
};
