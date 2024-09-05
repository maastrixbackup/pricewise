<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('shop_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('shop_products')->onDelete('cascade');
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2)->computed('quantity * price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_order_details');
    }
}
