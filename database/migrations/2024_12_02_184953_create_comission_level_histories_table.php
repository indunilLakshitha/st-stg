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
        Schema::create('comission_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->decimal('left_points', 20, 2)->default(0);
            $table->decimal('right_points', 20, 2)->default(0);
            $table->tinyInteger('type')->default(1)->comment('1 : ADDED 2: REMOVED');
            $table->tinyInteger('comission_method')->default(1)->comment('1 : GSC 2: DSC ');
            $table->integer('achieved_level')->nullable();
            $table->dateTime('achieved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comission_histories');
    }
};
