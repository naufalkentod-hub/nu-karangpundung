<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infaqs', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 32)->unique();
            $table->uuid('public_token')->unique();
            $table->uuid('idempotency_key')->unique();
            $table->string('donor_name', 120)->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->unsignedBigInteger('amount');
            $table->text('message')->nullable();
            $table->string('payment_method', 32)->default('manual_transfer');
            $table->string('payment_status', 20)->default('pending');
            $table->string('gateway_reference', 120)->nullable()->unique();
            $table->timestamp('manual_confirmation_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('verification_reference', 120)->nullable();
            $table->string('reviewed_by', 120)->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index(['payment_status', 'paid_at']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infaqs');
    }
};
