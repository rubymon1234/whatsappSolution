<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlanSubscriptionToCurrentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('current_plans', function (Blueprint $table) {
           $table->smallInteger('plan_subscription')->after('daily_count')->nullable()->comment('0-Daily,1-Monthly');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('current_plans', function (Blueprint $table) {
            //
        });
    }
}
