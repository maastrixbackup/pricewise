<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCartExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cart_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('product_carts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->bigInteger('ext_id');
            $table->double('amount', 10,2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_cart_extras');
    }
}
