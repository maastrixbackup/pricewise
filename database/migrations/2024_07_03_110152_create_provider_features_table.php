<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_features', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provider_id');
            $table->string('mobile_data');
            $table->string('call_text');
            $table->decimal('price', 20, 2);
            $table->dateTime('valid_till')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('provider_features');
    }
}
