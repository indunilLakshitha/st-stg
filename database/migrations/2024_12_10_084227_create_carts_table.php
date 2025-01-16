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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->index();
            $table->string('cart_status')->default('active');
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->boolean('is_checkout')->default(false);
            $table->tinyInteger('is_delete')->default(0);
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            $table->timestamps();
            // Add foreign key constraints if necessary
            $table->foreign('user_id')->references('id')->on('users');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
