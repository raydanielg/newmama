<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_orders', function (Blueprint $table) {
            $table->id();
            $table->string('ref')->unique();
            $table->date('posting_date');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('supplier_name')->nullable();
            $table->string('source_file_name')->nullable();
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->integer('total_lines')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('import_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_order_id')->constrained('import_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('qty', 12, 2);
            $table->decimal('unit_cost', 14, 2);
            $table->decimal('line_total', 15, 2);
            $table->timestamps();

            $table->index(['import_order_id']);
            $table->index(['product_id']);
            $table->index(['sku']);
            $table->index(['barcode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_order_lines');
        Schema::dropIfExists('import_orders');
    }
};
