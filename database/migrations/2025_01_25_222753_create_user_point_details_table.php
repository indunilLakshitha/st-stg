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
        Schema::create('user_point_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('left')->default(0);
            $table->decimal('right')->default(0);
            $table->decimal('left_balance_now')->default(0);
            $table->decimal('right_balance_now')->default(0);
            $table->decimal('withdrawed')->default(0);
            $table->decimal('wallet_balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_point_details');
    }
};
