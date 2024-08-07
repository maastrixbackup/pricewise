<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('sku')->nullable();
            $table->string('size')->nullable();
            $table->string('model');
            $table->bigInteger('brand_id');
            $table->bigInteger('color_id');
            $table->bigInteger('category_id');
            $table->string('actual_price');
            $table->string('sell_price');
            $table->string('delivery_cost')->nullable();
            $table->string('exp_delivery')->nullable();
            $table->string('qty')->nullable();
            $table->string('is_featured')->nullable();
            $table->json('heighlights')->nullable();
            $table->json('features')->nullable();
            $table->json('pin_codes')->nullable();
            $table->text('about')->nullable();
            $table->longText('description')->nullable();
            $table->longText('specification')->nullable();
            $table->enum('p_status', ['0', '1', '2', '3'])->default('0');
            $table->enum('is_publish', ['0', '1'])->default('0');
            $table->enum('product_type', ['personal', 'commercial', 'large-business'])->default('personal');
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
        Schema::dropIfExists('shop_products');
    }
}
