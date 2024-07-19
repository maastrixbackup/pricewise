<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->bigInteger('event_type')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->bigInteger('caterer_id')->nullable();
            $table->text('description')->nullable();
            $table->longText('location')->nullable();
            $table->longText('postal_code')->nullable();
            $table->string('house_no')->nullable();
            $table->bigInteger('room_type')->nullable();
            $table->string('image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('catering_price', 20, 2);
            $table->decimal('decoration_price', 20, 2);
            $table->decimal('photoshop_price', 20, 2);
            $table->enum('status', ['active', 'inactive'])->default('inactive');
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
        Schema::dropIfExists('events');
    }
}
