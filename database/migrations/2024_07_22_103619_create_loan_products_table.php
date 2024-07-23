<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->decimal('borrow_amount', 20, 2);
            $table->decimal('expected_amount', 20, 2);
            $table->string('approval_time')->nullable();
            $table->string('rate_of_interest')->nullable();
            $table->bigInteger('p_id');
            $table->bigInteger('category')->nullable();
            $table->json('provider')->nullable();
            $table->json('loan_type_id')->nullable();
            $table->json('pin_codes')->nullable();
            $table->string('product_type')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['1', '0'])->default('0');
            $table->timestamps();
            $table->softDeletes(); // Adds the deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_products');
    }
}
