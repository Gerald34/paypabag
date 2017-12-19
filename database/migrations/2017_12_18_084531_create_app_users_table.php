<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('paypa_id');
            $table->string('username');
            $table->string('password');
            $table->string('name');
            $table->string('surname');
            $table->string('email');
            $table->string('gender');
            $table->string('birth_date');
            $table->string('street_number');
            $table->string('street_name');
            $table->string('suburb');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->timestamps('created_at');
            $table->timestamps('last_login_at');
            $table->timestamps('logout_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_users');
    }
}
