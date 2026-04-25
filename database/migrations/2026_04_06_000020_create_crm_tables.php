<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_inbox_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('channel')->default('whatsapp');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index(['customer_id']);
        });

        Schema::create('crm_automations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trigger_type');
            $table->string('action_type');
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });

        Schema::create('crm_preorders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('product_name');
            $table->decimal('qty', 12, 2)->default(1);
            $table->date('expected_date')->nullable();
            $table->string('status')->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status']);
        });

        Schema::create('crm_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('referrer_name')->nullable();
            $table->string('referee_name');
            $table->string('referee_phone')->nullable();
            $table->decimal('reward_amount', 15, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['status']);
        });

        Schema::create('crm_loyalty_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->unique()->constrained('customers')->cascadeOnDelete();
            $table->decimal('points_balance', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('crm_loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_loyalty_account_id')->constrained('crm_loyalty_accounts')->cascadeOnDelete();
            $table->date('posting_date');
            $table->string('type');
            $table->decimal('points', 15, 2)->default(0);
            $table->string('reference')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['posting_date']);
            $table->index(['type']);
        });

        Schema::create('crm_feedback_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('message');
            $table->string('status')->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['rating']);
        });

        Schema::create('crm_upsell_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('channel')->default('whatsapp');
            $table->text('offer_text');
            $table->string('status')->default('draft');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['status']);
        });

        Schema::create('crm_upsell_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_upsell_campaign_id')->constrained('crm_upsell_campaigns')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('new');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_upsell_leads');
        Schema::dropIfExists('crm_upsell_campaigns');
        Schema::dropIfExists('crm_feedback_entries');
        Schema::dropIfExists('crm_loyalty_transactions');
        Schema::dropIfExists('crm_loyalty_accounts');
        Schema::dropIfExists('crm_referrals');
        Schema::dropIfExists('crm_preorders');
        Schema::dropIfExists('crm_automations');
        Schema::dropIfExists('crm_inbox_messages');
    }
};
