<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnergyElectricConnectionSlabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_electric_connection_slabs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cat');
            $table->string('meter_type_from')->nullable();
            $table->string('meter_type_to')->nullable();
            $table->string('usage_from')->nullable();
            $table->string('usage_to')->nullable();
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
        Schema::dropIfExists('energy_electric_connection_slabs');
    }
}
