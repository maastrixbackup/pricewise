<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pc_id')->constrained('postal_codes')->onDelete('cascade');
            $table->string('postal_codes')->nullable();
            $table->json('house_number')->nullable();
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
        Schema::dropIfExists('house_numbers');
    }
}
