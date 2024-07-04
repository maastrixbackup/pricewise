<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoColumnsToProviderDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provider_discounts', function (Blueprint $table) {
            $table->dateTime('valid_from')->nullable()->after('discount');
            $table->dateTime('valid_till')->nullable()->after('valid_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_discounts', function (Blueprint $table) {
            $table->dropColumn(['valid_from', 'valid_till']);
        });
    }
}
