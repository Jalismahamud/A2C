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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->decimal('amount');
            $table->tinyInteger('status')->default(0)->comment('0 => Pending, 1 =>Complete, 2=>Processing, 3=>Intransit');
            $table->tinyInteger('type')->default(0)->comment('0=>Out, 1=>In');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
