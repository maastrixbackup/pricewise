<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnergyFeedInChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_feed_in_charges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provider_id');
            $table->string('scale')->nullable();
            $table->string('range_from')->nullable();
            $table->string('range_to')->nullable();
            $table->string('cost_per_day')->nullable();
            $table->string('cost_per_year')->nullable();
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
        Schema::dropIfExists('energy_feed_in_charges');
    }
}
