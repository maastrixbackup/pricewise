<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacityTransportGasSlabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacity_transport_gas_slabs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cat');
            $table->string('meter_type')->nullable();
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
        Schema::dropIfExists('capacity_transport_gas_slabs');
    }
}
