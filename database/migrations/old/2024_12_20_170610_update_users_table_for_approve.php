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
            $table->unsignedBigInteger('assigned_user_id_on_approval')->after('assigned_user_id')->nullable()->index();
            $table->string('assigned_user_side_on_approval', 10)->after('assigned_user_id_on_approval')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('assigned_user_id_on_approval');
            $table->dropColumn('assigned_user_side_on_approval');
        });
    }
};
