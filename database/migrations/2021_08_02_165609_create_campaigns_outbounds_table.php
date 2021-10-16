<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsOutboundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_outbounds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('campaign_id')->unsigned();
            $table->string('instance_token')->nullable();
            $table->string('type')->nullable();
            $table->string('number');
            $table->text('message')->nullable();
            $table->string('sent_time')->nullable();
            $table->string('media_file_name')->nullable();
            $table->smallInteger('is_status')->nullable()->comment('0-Not sent,1-Sent')->default(0);
            $table->string('error_code')->nullable();
            $table->string('status_message')->nullable();
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
        Schema::dropIfExists('campaigns_outbounds');
    }
}
