<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeConditionApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_condition_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('reseller_id')->unsigned()->nullable();
            $table->string('name');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('sun')->nullable();
            $table->integer('mon')->nullable();
            $table->integer('tue')->nullable();
            $table->integer('wed')->nullable();
            $table->integer('thu')->nullable();
            $table->integer('fri')->nullable();
            $table->integer('sat')->nullable();
            $table->string('success_app_name')->nullable();
            $table->string('success_app_value')->nullable();
            $table->string('failed_app_name')->nullable();
            $table->string('failed_app_value')->nullable();
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
        Schema::dropIfExists('time_condition_applications');
    }
}
