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
        Schema::create('approve_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('approved_referrer_id')->nullable();
            $table->unsignedBigInteger('assigned_id_by_referrer')->nullable();
            $table->string('assigned_side_by_referrer', 10)->nullable();
            $table->dateTime('referrer_approved_at')->nullable();
            $table->unsignedBigInteger('approved_admin_id')->nullable();
            $table->unsignedBigInteger('actual_assigned_id')->nullable();
            $table->string('actual_assigned_side', 10)->nullable();
            $table->dateTime('admin_approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approve_histories');
    }
};
