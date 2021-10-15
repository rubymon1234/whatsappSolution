<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuInputDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_input_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('instance_token');
            $table->string('number')->nullable();
            $table->string('user_input')->nullable();
            $table->string('interactive_menu_id')->nullable();
            $table->string('app_name')->nullable();
            $table->string('app_value')->nullable();
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
        Schema::dropIfExists('menu_input_datas');
    }
}
