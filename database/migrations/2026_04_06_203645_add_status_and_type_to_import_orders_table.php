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
        Schema::table('import_orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('total_lines'); // pending, completed, cancelled
            $table->string('import_type')->default('product')->after('status'); // product, opening_stock, adjustment
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('import_orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'import_type']);
        });
    }
};
