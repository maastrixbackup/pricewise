<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnergyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->smallInteger('contract_length')->nullable();
            $table->double('power_cost_per_unit', 10, 2)->nullable();
            $table->double('gas_cost_per_unit', 10, 2)->nullable();
            $table->double('tax_on_electric', 10, 2)->nullable();
            $table->double('tax_on_gas', 10, 2)->nullable();
            $table->double('ode_on_electric', 10, 2)->nullable();
            $table->double('ode_on_gas', 10, 2)->nullable();
            $table->json('power_origin')->nullable();
            $table->json('type_of_current')->nullable();
            $table->json('type_of_gas')->nullable();
            $table->json('energy_label')->nullable();
            $table->double('fixed_delivery', 10, 2)->nullable();
            $table->double('grid_management', 10, 2)->nullable();
            $table->double('feed_in_tariff', 10, 2)->nullable();
            $table->double('vat', 10, 2)->nullable();
            $table->double('discount', 10, 2)->nullable();
            $table->date('valid_till')->nullable();
            $table->enum('status', ['1', '0'])->default('0');
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
        Schema::dropIfExists('energy_products');
    }
}
