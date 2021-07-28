<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_plans', function (Blueprint $table) {
            $table->increments('id');
            //Relation with plan
            $table->integer('plan_id')->unsigned()->nullable();
            $table->string('daily_count');
            $table->date('plan_validity');
            $table->string('instance_count')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('reseller_id')->unsigned()->nullable();
            $table->smallInteger('is_status')->nullable()->comment('0-Not Active,1-Active,2-pending for approvel,3-reject')->default(true);
            
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
        Schema::dropIfExists('current_plans');
    }
}
