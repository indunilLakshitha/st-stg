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
            $table->unsignedBigInteger('dashboard_district_id')->nullable()->index()
                ->after('address');
            $table->unsignedBigInteger('dashboard_city_id')->nullable()->index()
                ->after('dashboard_district_id');
            $table->string('gender', 20)->nullable()->index()
                ->after('dashboard_city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('district_id');
            $table->dropColumn('dashboard_city_id');
            $table->dropColumn('gender');
        });
    }
};
