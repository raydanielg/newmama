<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('mothers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('whatsapp_number')->unique();
            $table->foreignId('region_id')->constrained();
            $table->foreignId('district_id')->constrained();
            $table->string('status'); // pregnant, new_parent, trying
            $table->date('edd_date')->nullable();
            $table->integer('baby_age')->nullable(); // in months
            $table->string('trying_duration')->nullable();
            $table->string('current_step')->default('3');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mothers');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('regions');
    }
};
