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
        Schema::create('loan_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Step 1: Loan Details
            $table->string('loan_type');
            $table->decimal('loan_amount', 15, 2);
            $table->string('loan_tenure');

            // Step 2: Income & Employment
            $table->string('employment_status');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('gross_salary', 15, 2);

            $table->decimal('purposed_loan_rental', 15, 2)->nullable();
            $table->text('past_default_loan')->nullable();
            
            // Step 3: Financial Situation
            $table->integer('active_loans_count')->default(0);

            // Step 4: Required Uploads
            $table->string('nic_copy')->nullable();
            $table->json('salary_slips')->nullable(); // Multiple slips
            $table->json('bank_statements')->nullable(); // Multiple statements
            $table->string('employment_letter')->nullable();
            $table->json('optional_uploads')->nullable();

            $table->string('status')->default('Submitted for verification');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_requests');
    }
};
