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
        Schema::create('subsidary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',60)->unique()->nullable();
            $table->string('subsidary_number',100)->nullable();
            $table->integer('location_id');
            $table->string('po_box',50)->nullable();
            $table->text('address',255)->nullable();
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
        Schema::dropIfExists('subsidary');
    }
};
