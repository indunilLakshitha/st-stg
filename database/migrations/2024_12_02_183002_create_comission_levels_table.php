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
        Schema::create('comission_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level');
            $table->boolean('is_premium')->default(false);
            $table->integer('left_points')->default(0);
            $table->integer('right_points')->default(0);
            $table->integer('point_value')->default(0)->comment('In LKR');
            $table->tinyInteger('status')->default(1)->comment('1 : active 2: inactive');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('left_points')->after('points')->default(0);
            $table->integer('right_points')->after('left_points')->default(0);
            $table->integer('comission_level')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comission_levels');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('comission_level');
            $table->dropColumn('left_points');
            $table->dropColumn('left_points');
        });
    }
};
