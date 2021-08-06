<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlacklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable();
            $table->smallInteger('is_status')->nullable()->comment('1-Active, 0-InActive')->default(1);
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
        Schema::dropIfExists('blacklists');
    }
}
