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
        Schema::table('pos_orders', function (Blueprint $table) {
            $table->string('whatsapp_session_id')->nullable()->after('customer_id');
            $table->timestamp('expires_at')->nullable()->after('status');
            $table->string('payment_link_token')->nullable()->unique()->after('expires_at');
        });

        Schema::table('product_requests', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('status');
            $table->string('payment_link_token')->nullable()->unique()->after('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_orders', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_session_id', 'expires_at', 'payment_link_token']);
        });

        Schema::table('product_requests', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'payment_link_token']);
        });
    }
};
