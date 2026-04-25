<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->date('posting_date');
            $table->string('description')->nullable();
            $table->string('journal_type');
            $table->string('source_type')->nullable();
            $table->string('source_ref')->nullable();
            $table->string('posted_by')->nullable();
            $table->string('status')->default('posted');
            $table->string('branch')->nullable();
            $table->timestamps();

            $table->index(['journal_type', 'posting_date']);
            $table->index(['source_type', 'source_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};
