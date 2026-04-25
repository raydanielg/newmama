<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elms_course_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('elms_courses')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('amount', 14, 2)->default(0);
            $table->char('currency', 3)->default('TZS');
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['course_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elms_course_fees');
    }
};
