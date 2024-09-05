<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('order_number')->unique();
            $table->string('transaction_id')->nullable();
            $table->integer('user_type')->nullable();
            $table->integer('salutation')->nullable();
            $table->string('guest_fname')->nullable();
            $table->string('guest_lname')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_status')->default('pending'); // Example statuses: pending, processing, completed, canceled
            $table->string('payment_method')->nullable(); // Example: credit card, PayPal, etc.
            $table->timestamp('order_date')->nullable();
            $table->date('exp_delivery')->nullable();
            $table->text('company_details')->nullable();
            $table->text('shipping_address')->nullable();
            $table->integer('bill_different')->nullable();
            $table->text('billing_address')->nullable();
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
        Schema::dropIfExists('shop_orders');
    }
}
