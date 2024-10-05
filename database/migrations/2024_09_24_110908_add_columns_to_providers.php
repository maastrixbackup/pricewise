<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->double('fixed_deliver_cost', 10, 2)->nullable()->after('image');
            $table->double('grid_management_cost', 10, 2)->nullable()->after('fixed_deliver_cost');
            $table->double('feed_in_tariff', 10, 2)->nullable()->after('grid_management_cost');
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->double('fixed_deliver_cost');
            $table->double('grid_management_cost');
            $table->double('feed_in_tariff');
        });
    }
}
