<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('instance_token');
            $table->string('number')->nullable();
            $table->text('user_input')->nullable();
            $table->string('app_name')->nullable();
            $table->string('app_value')->nullable();
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
        Schema::dropIfExists('log_sessions');
    }
}
