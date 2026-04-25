<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            // Only add columns if they don't exist
            if (!Schema::hasColumn('whatsapp_messages', 'to_number')) {
                $table->string('to_number')->nullable()->after('body');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'message')) {
                $table->text('message')->nullable()->after('to_number');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'status')) {
                $table->string('status')->default('pending')->after('type');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'external_id')) {
                $table->string('external_id')->nullable()->after('wa_message_id');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'metadata')) {
                $table->json('metadata')->nullable()->after('external_id');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'response_data')) {
                $table->json('response_data')->nullable()->after('metadata');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('sent_at');
            }
            if (!Schema::hasColumn('whatsapp_messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('delivered_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            // Only drop columns that exist
            $columnsToDrop = [];
            if (Schema::hasColumn('whatsapp_messages', 'to_number')) {
                $columnsToDrop[] = 'to_number';
            }
            if (Schema::hasColumn('whatsapp_messages', 'message')) {
                $columnsToDrop[] = 'message';
            }
            if (Schema::hasColumn('whatsapp_messages', 'status')) {
                $columnsToDrop[] = 'status';
            }
            if (Schema::hasColumn('whatsapp_messages', 'external_id')) {
                $columnsToDrop[] = 'external_id';
            }
            if (Schema::hasColumn('whatsapp_messages', 'metadata')) {
                $columnsToDrop[] = 'metadata';
            }
            if (Schema::hasColumn('whatsapp_messages', 'response_data')) {
                $columnsToDrop[] = 'response_data';
            }
            if (Schema::hasColumn('whatsapp_messages', 'delivered_at')) {
                $columnsToDrop[] = 'delivered_at';
            }
            if (Schema::hasColumn('whatsapp_messages', 'read_at')) {
                $columnsToDrop[] = 'read_at';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
