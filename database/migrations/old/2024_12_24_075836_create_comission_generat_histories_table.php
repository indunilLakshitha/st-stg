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
        Schema::create('comission_generat_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('run_by')->default(0)->comment('0 = system');
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->tinyInteger('status')->nullable()->comment('1 =  completed 2 = failed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comission_generat_histories');
    }
};
