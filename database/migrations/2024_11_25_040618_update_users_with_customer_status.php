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
            $table->boolean('is_customer')->after('is_admin')->default(true);
            $table->tinyInteger('payment_type')->after('is_customer')->default(1)->comment('1 = bank deposite 2 =  online');
            $table->tinyInteger('payment_status')->after('payment_type')->default(1)->comment('1 = PENDING 2 =  HALF 3 = FULL');
            $table->boolean('is_tree_enabled')->after('payment_status')->default(false);
            $table->unsignedBigInteger('referrer_id')->after('parent_id')->index()->nullable();
            $table->boolean('approved_by_admin')->after('is_customer')->default(false)->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_customer');
            $table->dropColumn('payment_type');
            $table->dropColumn('payment_status');
            $table->dropColumn('is_tree_enabled');
            $table->dropColumn('referrer_id');
            $table->dropColumn('approved_by_admin');
        });
    }
};
