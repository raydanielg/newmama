<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_number')->unique();
            $table->string('customer_type');
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('segment')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->unsignedInteger('credit_period')->default(0);
            $table->string('payment_terms')->default('COD');
            $table->decimal('balance', 15, 2)->default(0);
            $table->unsignedInteger('crown_points')->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('last_purchase_date')->nullable();
            $table->decimal('last_purchase_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['customer_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
