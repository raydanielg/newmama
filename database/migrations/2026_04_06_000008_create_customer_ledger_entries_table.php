<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->date('posting_date');
            $table->string('document_type');
            $table->string('document_ref');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->boolean('is_open')->default(false);
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index(['customer_id', 'posting_date']);
            $table->index(['document_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_ledger_entries');
    }
};
