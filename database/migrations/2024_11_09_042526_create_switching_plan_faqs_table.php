<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwitchingPlanFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('switching_plan_faqs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provider_id');
            $table->bigInteger('cat_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('question');
            $table->text('answer')->nullable();
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
        Schema::dropIfExists('switching_plan_faqs');
    }
}
