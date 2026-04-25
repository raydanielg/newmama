<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elms_trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('specialization')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('elms_course_trainer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('elms_courses')->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('elms_trainers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['course_id', 'trainer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elms_course_trainer');
        Schema::dropIfExists('elms_trainers');
    }
};
