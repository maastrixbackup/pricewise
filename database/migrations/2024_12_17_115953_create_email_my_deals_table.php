<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailMyDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_my_deals', function (Blueprint $table) {
            $table->id();
            $table->string('email_id')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('house_no')->nullable();
            $table->string('address')->nullable();
            $table->string('electric_consume')->nullable();
            $table->string('gas_consume')->nullable();
            $table->string('return')->nullable();
            $table->string('no_of_person')->nullable();
            $table->string('url')->nullable();
            $table->text('service_ids')->nullable();
            $table->date('valid_till')->nullable();
            $table->integer('email_send');
            $table->string('page_type')->nullable();
            $table->enum('status', ['1', '0'])->default('0');
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
        Schema::dropIfExists('email_my_deals');
    }
}
