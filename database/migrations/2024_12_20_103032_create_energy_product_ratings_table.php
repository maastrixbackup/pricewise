<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnergyProductRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_product_ratings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('provider_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->string('general')->nullable();
            $table->string('online')->nullable();
            $table->string('sunstainable')->nullable();
            $table->string('transfer')->nullable();
            $table->string('price_quality')->nullable();
            $table->string('info_provision')->nullable();
            $table->string('recommend')->nullable();
            $table->string('billing')->nullable();
            $table->string('ratings')->nullable();
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
        Schema::dropIfExists('energy_product_ratings');
    }
}
