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
        Schema::create('point_adding_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('run_by')->nullable();
            $table->dateTime('started_at');
            $table->text('left_point_id_list')->nullable();
            $table->text('right_point_id_list')->nullable();
            $table->integer('points')->nullable()->default(0);
            $table->dateTime('ended_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_adding_histories');
    }
};
