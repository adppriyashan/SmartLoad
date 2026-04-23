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
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_request_id')->constrained()->onDelete('cascade');
            
            // Step 5: Guarantor Documents
            $table->string('name');
            $table->string('nic');
            $table->string('address');
            $table->integer('age');
            $table->string('job_title');

            // Step 6: Guarantor Uploads
            $table->string('nic_copy')->nullable();
            $table->json('salary_slips')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guarantors');
    }
};
