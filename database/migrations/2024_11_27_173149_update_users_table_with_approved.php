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
            $table->unsignedBigInteger('approved_by')->after('approved_by_admin')->index()->nullable()->comment('');
            $table->unsignedBigInteger('assigned_user_id')->after('approved_by_admin')->index()->nullable();
            $table->string('assigned_user_side', 10)->after('assigned_user_id')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('approved_by_ref');
            $table->dropColumn('assigned_user_id');
            $table->dropColumn('assigned_user_side');
        });
    }
};
