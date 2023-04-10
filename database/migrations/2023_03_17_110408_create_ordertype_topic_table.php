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
        Schema::create('ordertype_topic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_type')->unsigned()->index();
            $table->foreign('order_type')->references('id')->on('otype')->onDelete('cascade');
            $table->bigInteger('topic_id')->unsigned()->index();
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordertype_topic');
    }
};
