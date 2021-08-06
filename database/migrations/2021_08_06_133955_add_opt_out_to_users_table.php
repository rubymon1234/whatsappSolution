<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptOutToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->smallInteger('opt_out')->nullable()->comment('1-Active, 0-InActive')->after('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            //
        });
    }
}
