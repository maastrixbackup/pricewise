<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('provider_id')->nullable();
            $table->enum('user_type',['personal', 'business', 'large_business'])->default('inactive');
            $table->bigInteger('category')->nullable();
            $table->bigInteger('sub_category')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->string('service_type')->nullable();
            $table->string('combos')->nullable();
            $table->longText('advantages')->nullable();
            $table->string('postal_code')->nullable();
            $table->float('total_price', 10, 5)->nullable();
            $table->float('discounted_price', 10, 5)->nullable();
            $table->float('discounted_prct', 10, 2)->nullable();
            $table->float('commission_amt', 10, 5)->nullable();
            $table->float('commission_prct',10, 2)->nullable();
            $table->tinyInteger ('solar_panels')->nullable();
            $table->tinyInteger ('no_gas')->nullable();
            $table->string('request_status');
            $table->string('shipping_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('order_no')->nullable();
            $table->json('contact_details')->nullable();
            $table->json('additional_information')->nullable();
            $table->json('additional_questions')->nullable();
            $table->json('delivery')->nullable();
            $table->json('verification')->nullable();
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
        Schema::dropIfExists('user_requests');
    }
}
