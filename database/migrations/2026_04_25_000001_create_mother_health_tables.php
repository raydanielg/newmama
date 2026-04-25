<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Appointments table
        Schema::create('mother_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('appointment_date');
            $table->string('clinic_name')->nullable();
            $table->string('doctor_name')->nullable();
            $table->enum('type', ['checkup', 'ultrasound', 'lab_test', 'vaccination', 'other'])->default('checkup');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'missed'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->text('outcome')->nullable();
            $table->timestamps();
        });

        // Weight logs table
        Schema::create('mother_weight_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->decimal('weight_kg', 5, 2);
            $table->integer('weeks_pregnant')->nullable();
            $table->date('recorded_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Baby kick counts table
        Schema::create('mother_kick_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->integer('kick_count');
            $table->integer('duration_minutes')->default(60);
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->date('recorded_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Blood pressure logs table
        Schema::create('mother_blood_pressures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->integer('systolic'); // mmHg
            $table->integer('diastolic'); // mmHg
            $table->integer('heart_rate')->nullable(); // bpm
            $table->dateTime('recorded_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Health alerts/risk assessments table
        Schema::create('mother_health_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->string('alert_type'); // preeclampsia, anemia, gestational_diabetes, etc.
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->text('message');
            $table->text('recommendation');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        // Pregnancy checklist items
        Schema::create('mother_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->string('category'); // nutrition, exercise, tests, preparation
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('recommended_week')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Daily symptoms/mood tracker
        Schema::create('mother_daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mother_id')->constrained('mothers')->onDelete('cascade');
            $table->date('log_date');
            $table->enum('mood', ['great', 'good', 'okay', 'tired', 'sad', 'anxious'])->nullable();
            $table->json('symptoms')->nullable(); // array of symptoms
            $table->text('notes')->nullable();
            $table->decimal('sleep_hours', 3, 1)->nullable();
            $table->integer('water_intake_glasses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mother_daily_logs');
        Schema::dropIfExists('mother_checklist_items');
        Schema::dropIfExists('mother_health_alerts');
        Schema::dropIfExists('mother_blood_pressures');
        Schema::dropIfExists('mother_kick_counts');
        Schema::dropIfExists('mother_weight_logs');
        Schema::dropIfExists('mother_appointments');
    }
};
