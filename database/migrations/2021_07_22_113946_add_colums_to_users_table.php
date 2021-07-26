<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->smallInteger('is_status')->nullable()->comment('0-Not Active,1-Active,2-pending,3-block')->after('remember_token');
            $table->integer('domain_id')->nullable()->after('remember_token');
            $table->integer('profile_id')->nullable()->after('remember_token');
            $table->char('mobile', '25')->nullable()->after('remember_token');
            $table->integer('reseller_id')->nullable()->after('remember_token');
            $table->integer('plan_id')->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
