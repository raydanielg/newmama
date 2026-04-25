<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->string('type')->default('general')->after('message');
            $table->string('status')->default('pending')->after('type');
            $table->string('external_id')->nullable()->after('status');
            $table->json('metadata')->nullable()->after('external_id');
            $table->json('response_data')->nullable()->after('metadata');
            $table->timestamp('delivered_at')->nullable()->after('sent_at');
            $table->timestamp('read_at')->nullable()->after('delivered_at');
            $table->index(['to_number', 'status']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropIndex(['to_number', 'status']);
            $table->dropIndex(['type', 'status']);
            $table->dropColumn(['type', 'status', 'external_id', 'metadata', 'response_data', 'delivered_at', 'read_at']);
        });
    }
};
