<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->string('type');
            $table->date('posting_date');
            $table->string('description')->nullable();
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->string('status')->default('posted');
            $table->string('branch')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('journal_id')->nullable()->constrained('journals')->nullOnDelete();
            $table->string('payment_method')->nullable();
            $table->string('notes')->nullable();
            $table->string('posted_by')->nullable();
            $table->timestamps();

            $table->index(['type', 'posting_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
