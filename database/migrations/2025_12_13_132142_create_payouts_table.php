<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_id')->unique();
            $table->foreignId('propfirm_id')->constrained('users')->cascadeOnDelete();
            
            // Beneficiary details
            $table->string('beneficiary_name');
            $table->string('beneficiary_email');
            $table->string('beneficiary_account_number')->nullable();
            $table->string('beneficiary_bank')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('upi_id')->nullable();
            $table->enum('payment_type', ['upi', 'bank'])->default('bank');
            
            // Payout details
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            
            // Status: 0=pending, 1=rejected, 2=confirmed, 3=released, 4=proof_uploaded, 5=final_payout, 6=completed
            $table->tinyInteger('status')->default(0);
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('propfirm_id');
            $table->index('status');
            $table->index('payout_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
