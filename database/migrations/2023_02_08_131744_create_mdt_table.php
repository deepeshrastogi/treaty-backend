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
        Schema::create('mdt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',50)->nullable();
            $table->string('business',100)->nullable();
            $table->string('street',255)->nullable();
            $table->string('number',255)->nullable();
            $table->bigInteger('parent_id')->default(0);
            $table->string('zipcode',50)->nullable();
            $table->string('city',50)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('email',60)->nullable();
            $table->string('fax',60)->nullable();
            $table->string('department',100)->nullable();
            $table->string('supplier_number',20)->nullable();
            $table->string('maximum_data_usage',50)->nullable();
            $table->string('granted_number_of_user',10)->nullable();
            $table->string('profile_id',10)->nullable();
            $table->tinyInteger('is_active')->default(1)->comment('1 active, 2 inactive');
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
        Schema::dropIfExists('mdt');
    }
};
