<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->string('investor_number')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('id_number')->nullable();
            $table->string('address')->nullable();
            $table->string('status')->default('active');
            $table->decimal('balance', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['name']);
        });

        Schema::create('investor_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')->constrained('investors')->cascadeOnDelete();
            $table->date('posting_date');
            $table->string('type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('reference')->nullable();
            $table->string('method')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['posting_date']);
            $table->index(['type']);
            $table->index(['investor_id', 'posting_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_transactions');
        Schema::dropIfExists('investors');
    }
};
