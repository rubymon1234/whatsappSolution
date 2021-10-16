<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataCaptureLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_capture_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('instance_token');
            $table->string('capture_application_id')->nullable();
            $table->string('number')->nullable();
            $table->string('user_input')->nullable();
            $table->smallInteger('is_status')->nullable();
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
        Schema::dropIfExists('data_capture_logs');
    }
}
