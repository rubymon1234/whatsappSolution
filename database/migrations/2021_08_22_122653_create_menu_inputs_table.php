<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_inputs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('interactive_menu_id')->unsigned()->nullable();
            $table->string('input_key');
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
        Schema::dropIfExists('menu_inputs');
    }
}
