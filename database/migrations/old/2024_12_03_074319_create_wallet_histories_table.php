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
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->index('user_id');
            $table->unsignedBigInteger('wallet_id');
            $table->index('wallet_id');
            $table->decimal('amount',20,2)->default(0);
            $table->decimal('balance',20,2)->default(0);
            $table->dateTime('requested_at')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();

            $table->tinyInteger('comission_type')->default(1)->comment('1 : REFERRAL 2: DIRECT');
            $table->tinyInteger('type')->default(1)->comment('1 : ADDED 2: REMOVED');
            $table->tinyInteger('status')->default(1)->comment('1 : REQUESTED 2: TRANSFERED 3:CANSELED');
            $table->unsignedBigInteger('paid_by')->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_histories');
    }
};
