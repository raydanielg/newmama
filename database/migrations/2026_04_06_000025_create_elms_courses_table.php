<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elms_courses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('level', 50)->nullable();
            $table->unsignedInteger('duration_hours')->nullable();
            $table->decimal('base_price', 14, 2)->default(0);
            $table->char('currency', 3)->default('TZS');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
            $table->index(['category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elms_courses');
    }
};
