<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            if (!Schema::hasColumn('mothers', 'country_id')) {
                $table->foreignId('country_id')->nullable()->after('whatsapp_number')->constrained('countries')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('mothers', function (Blueprint $table) {
            if (Schema::hasColumn('mothers', 'country_id')) {
                $table->dropConstrainedForeignId('country_id');
            }
        });
    }
};
