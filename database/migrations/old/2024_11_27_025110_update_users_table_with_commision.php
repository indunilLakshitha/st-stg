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
            $table->bigInteger('points')->after('password')->default(0)->index()->nullable();
            $table->boolean('agent_a1_active')->after('is_admin')->default(true);
            $table->boolean('agent_a2_active')->after('agent_a1_active')->default(false);
            $table->unsignedBigInteger('agent_a1_child_id')->after('agent_a2_active')->index()->nullable();
            $table->unsignedBigInteger('agent_a2_child_id')->after('agent_a1_child_id')->index()->nullable();
            $table->bigInteger('agent_a1_points')->after('points')->default(0)->index()->nullable();
            $table->bigInteger('agent_a2_points')->after('agent_a1_points')->default(0)->index()->nullable();
            $table->text('path')->after('agent_a2_points')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('agent_a1_active');
            $table->dropColumn('agent_a2_active');
            $table->dropColumn('agent_a1_child_id');
            $table->dropColumn('agent_a2_child_id');
            $table->dropColumn('agent_a1_points');
            $table->dropColumn('agent_a2_points');
            $table->dropColumn('points');
            $table->dropColumn('path');
        });
    }
};
