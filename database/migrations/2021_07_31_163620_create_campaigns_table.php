<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('campaign_name');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('reseller_id')->unsigned()->nullable();
            $table->integer('current_plan_id')->unsigned()->nullable();
            $table->text('leads_file')->nullable(); // csv file
            $table->string('instance_token');
            $table->string('instance_name')->nullable();
            $table->string('type')->nullable();
            $table->text('message')->nullable();
            $table->string('media_file_name')->nullable(); // 
            $table->char('count',128)->nullable();
            $table->smallInteger('is_status')->nullable()->comment('0-Queued,2-Sending,1-Sent')->default(0);
            $table->timestamp('start_at')->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}
