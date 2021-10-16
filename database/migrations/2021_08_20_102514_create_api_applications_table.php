<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('reseller_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('url');
            $table->string('app_name')->nullable();
            $table->string('app_value')->nullable();
            $table->string('parameter_input')->nullable();
            $table->string('parameter_mobile')->nullable();
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
        Schema::dropIfExists('api_applications');
    }
}
