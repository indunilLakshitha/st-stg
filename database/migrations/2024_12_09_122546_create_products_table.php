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
        // Schema::create('products', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('category_id')->index('category_id');
        //     $table->unsignedBigInteger('sub_category_id')->nullable()->index('category_id');
        //     $table->text('description')->nullable();
        //     $table->decimal('price', 20, 2)->default(0)->nullable();
        //     $table->decimal('discount', 20, 2)->default(0)->nullable();
        //     $table->integer('qty')->default(0)->nullable();
        //     $table->boolean('qty_availability')->default(false)->nullable();

        //     $table->tinyInteger('status')->default(0)->nullable()->comment('1= active');

        //     $table->softDeletes();

        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
