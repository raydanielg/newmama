<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('posting_date');
            $table->string('document_type');
            $table->string('document_ref');
            $table->string('description')->nullable();
            $table->decimal('amount_tzs', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->boolean('is_open')->default(false);
            $table->date('due_date')->nullable();
            $table->string('journal_id')->nullable();
            $table->string('import_order_ref')->nullable();
            $table->timestamps();

            $table->index(['supplier_id', 'posting_date']);
            $table->index(['document_ref']);
            $table->index(['import_order_ref']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_ledger_entries');
    }
};
