<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalEnergySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_energy_settings', function (Blueprint $table) {
            $table->id();
            $table->double('tax_on_electric', 10, 2)->nullable();
            $table->double('tax_on_gas', 10, 2)->nullable();
            $table->double('ode_on_electric', 10, 2)->nullable();
            $table->double('ode_on_gas', 10, 2)->nullable();
            $table->double('vat', 10, 2)->nullable();
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
        Schema::dropIfExists('global_energy_settings');
    }
}
