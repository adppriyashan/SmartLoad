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
        Schema::table('users', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->string('image')->nullable();
            $table->string('nic')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('tel')->nullable();
            $table->date('dob')->nullable();
            $table->string('job')->nullable();
            $table->boolean('is_profile_complete')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'image',
                'nic',
                'address',
                'tel',
                'dob',
                'job',
                'is_profile_complete'
            ]);
        });
    }
};
