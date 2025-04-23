<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacityTransportTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacity_transport_tariffs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('op_id');
            $table->bigInteger('slab_id');
            $table->string('source')->nullable();
            $table->string('tariff_rate')->nullable();
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
        Schema::dropIfExists('capacity_transport_tariffs');
    }
}
