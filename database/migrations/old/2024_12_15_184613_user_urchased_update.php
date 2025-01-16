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
        Schema::table('user_puchased_courses', function (Blueprint $table) {
            $table->integer('purchased_percent')->nullable()->index()
                ->after('status')
                ->default(1)->comment('1 = HALF 2 = FULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_puchased_courses', function (Blueprint $table) {
            $table->dropColumn('purchased_percent');
        });
    }
};
