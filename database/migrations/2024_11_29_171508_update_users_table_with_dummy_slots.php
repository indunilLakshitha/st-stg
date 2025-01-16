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
            $table->unsignedBigInteger('dummy_a1_id')->nullable()->index()->comment(' This get assigned child user is of A1 -> only in DUMMY ACCOUNT');
            $table->unsignedBigInteger('dummy_a2_id')->nullable()->index()->comment(' This get assigned child user is of A2 -> only in DUMMY ACCOUNT');
            $table->boolean('approved_by_referrer')->default(false)->index()->after('is_customer');
            $table->unsignedBigInteger('approved_referrer_id')->nullable()->index()->after('approved_by_referrer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dummy_a1_id');
            $table->dropColumn('dummy_a2_id');
            $table->dropColumn('approved_by_referrer');
            $table->dropColumn('approved_referrer_id');
        });
    }
};
