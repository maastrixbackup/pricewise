<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnergyGridOperatersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('energy_grid_operaters', function (Blueprint $table) {
            $table->id();
            $table->string('operater_name');
            $table->string('current_transport_fee')->nullable();
            $table->string('gas_transport_fee')->nullable();
            $table->string('image')->nullable();
            $table->text('about')->nullable();
            $table->longText('postal_code')->nullable();
            $table->enum('status', [1, 0])->default(1);
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
        Schema::dropIfExists('energy_grid_operaters');
    }
}
