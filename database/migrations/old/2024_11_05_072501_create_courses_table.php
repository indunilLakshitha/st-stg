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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('discount_status')->default(0)->comment(' 0 no discount 1 discount available');
            $table->decimal('display_price',20,2)->default(0)->nullable();
            $table->decimal('discount',20,2)->default(0)->nullable();
            $table->decimal('course_price',20,2)->default(0)->nullable();
            $table->decimal('referer_commission',20,2)->default(0);
            $table->integer('course_point')->default(0);
            $table->decimal('installment_1',20,2)->default(0);
            $table->decimal('installment_2',20,2)->default(0);
            $table->tinyInteger('has_website')->default(0)->comment('0 = no website 1 =  has website');
            $table->tinyInteger('status')->default(1)->comment('0 = inactive 1 =  active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
