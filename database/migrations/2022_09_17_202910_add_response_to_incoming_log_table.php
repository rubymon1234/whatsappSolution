<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResponseToIncomingLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incoming_logs', function (Blueprint $table) {
           $table->smallInteger('sent_webhook_url')->after('response_request')->nullable()->comment('1-yes,0-NO')->default(0);
            $table->string('web_hook_url_response_code')->after('response_request')->nullable();
            $table->string('web_hook_url_response')->after('response_request')->nullable();
            $table->datetime('web_hook_url_start_time')->after('response_request')->nullable();
            $table->datetime('web_hook_url_end_time')->after('response_request')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incoming_log', function (Blueprint $table) {
            //
        });
    }
}
