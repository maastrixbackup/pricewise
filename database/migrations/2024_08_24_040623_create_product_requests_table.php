<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->string('user_name');
            $table->string('email');
            $table->string('phone_number');
            $table->integer('qty')->nullable();
            $table->string('delivery_address');
            $table->time('curr_time');
            $table->dateTime('callback_date');
            $table->text('additional_info')->nullable();
            $table->integer('terms_condition');
            $table->enum('status', ['1', '0'])->default('0');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraint to the products table
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_requests');
    }
}
