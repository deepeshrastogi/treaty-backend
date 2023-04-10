<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->integer('twofactor_code')->after('remember_token')->nullable();
            $table->integer('code_expire_time')->after('twofactor_code')->nullable();
            $table->boolean('is_two_factor')->default(0)->after('code_expire_time');
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
            $table->integer('twofactor_code');
            $table->integer('code_expire_time');
            $table->integer('is_two_factor');
        });
    }
};
