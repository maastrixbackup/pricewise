<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCyberSecuritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cyber_securities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('product_type')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->json('provider_id');
            $table->json('pin_codes')->nullable();
            $table->json('features')->nullable();
            $table->string('license_duration')->nullable();
            $table->string('cloud_backup')->nullable();
            $table->string('no_of_pc')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 20, 2)->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
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
        Schema::dropIfExists('cyber_securities');
    }
}
