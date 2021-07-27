<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('domain_name');
            $table->string('layout')->nullable();
            $table->string('company_name')->nullable();
            $table->integer('owner_id');
            $table->integer('reseller_id')->nullable();
            $table->integer('user_role_id');
            $table->integer('reseller_role_id');
            $table->boolean('is_active')->default(true)->comment('1-Active, 0-Inactive');
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
        Schema::dropIfExists('domains');
    }
}
