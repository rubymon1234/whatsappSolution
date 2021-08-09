<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScrubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scrubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scrub_name');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('reseller_id')->unsigned()->nullable();
            $table->integer('current_plan_id')->unsigned()->nullable();
            $table->text('leads_file')->nullable(); // csv file
            $table->string('instance_token');
            $table->string('instance_name')->nullable();
            $table->char('count',128)->nullable();
            $table->text('registered_file')->nullable(); // registered file
            $table->text('not_registered_file')->nullable(); // not_registered file
            $table->char('registered_count',200)->nullable();
            $table->char('not_registered_count',200)->nullable();
            $table->smallInteger('is_status')->nullable()->comment('1-Active, 0-InActive,2-pending')->default(1);
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
        Schema::dropIfExists('scrubs');
    }
}
