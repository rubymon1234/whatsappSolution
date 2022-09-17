<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboundMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbound_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->string('method')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('reseller_id')->unsigned();
            $table->string('instance_token')->nullable();
            $table->string('messaging_product')->nullable();
            $table->string('message')->nullable();
            $table->smallInteger('reply_status')->nullable()->comment('0-Not Sent,1-sent')->default(1);
            $table->string('json_data')->nullable();
            $table->string('web_hook_url_response_code')->nullable();
            $table->string('web_hook_url_response')->nullable();
            $table->datetime('web_hook_url_start_time')->nullable();
            $table->datetime('web_hook_url_end_time')->nullable();
            $table->smallInteger('is_status')->nullable()->comment('0-Not Active,1-Active')->default(1);
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
        Schema::dropIfExists('inbound_messages');
    }
}
