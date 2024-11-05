<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUserRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_requests', function (Blueprint $table) {
            $table->string('sex')->nullable();
            $table->string('initials')->nullable();
            $table->string('first_name')->nullable();
            $table->string('interjections')->nullable();
            $table->string('surname')->nullable();
            $table->string('age')->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('account_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('landline_number')->nullable();
            $table->string('post_code')->nullable();
            $table->string('house_number_add')->nullable();
            $table->string('company_name')->nullable();
            $table->string('chamber_of_commerce')->nullable();
            $table->string('function')->nullable();
            $table->string('branch')->nullable();
            $table->double('fix_delivery', 10, 2)->nullable();
            $table->double('grid_management', 10, 2)->nullable();
            $table->double('power_cost_per_unit', 10, 2)->nullable();
            $table->double('gas_cost_per_unit', 10, 2)->nullable();
            $table->double('tax_on_electric', 10, 2)->nullable();
            $table->double('tax_on_gas', 10, 2)->nullable();
            $table->double('ode_on_electric', 10, 2)->nullable();
            $table->double('ode_on_gas', 10, 2)->nullable();
            $table->double('vat', 10, 2)->nullable();
            $table->double('discount', 10, 2)->nullable();
            $table->double('feed_in_tariff', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_requests', function (Blueprint $table) {
            $table->dropColumn([
                'sex',
                'initials',
                'first_name',
                'interjections',
                'surname',
                'age',
                'dob',
                'email',
                'account_number',
                'mobile_number',
                'landline_number',
                'post_code',
                'house_number_add',
                'company_name',
                'chamber_of_commerce',
                'function',
                'branch',
                'fix_delivery',
                'grid_management',
                'power_cost_per_unit',
                'gas_cost_per_unit',
                'tax_on_electric',
                'tax_on_gas',
                'ode_on_electric',
                'ode_on_gas',
                'vat',
                'discount',
                'feed_in_tariff',
            ]);
        });
    }
}
