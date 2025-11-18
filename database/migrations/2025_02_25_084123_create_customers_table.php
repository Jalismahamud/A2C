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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('nid_number')->nullable();
            $table->string('school_name')->nullable();
            $table->string('teacher_name')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('license_number')->nullable();
            $table->text('address');
            $table->string('type')->default('general')->comment('student, general, driver');
            $table->tinyInteger('status')->default(0)->comment('0 => Inactive, 1 =>Active');
            $table->tinyInteger('approved')->default(0)->comment('0 => Not Approved, 1 =>Approved');
            $table->string('image')->nullable();
            $table->bigInteger('created_by');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
