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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_code',60)->after('status')->nullable();
            $table->integer('mdt_id')->after('order_code')->nullable();
            $table->integer('markt_id')->after('mdt_id')->nullable();
            $table->integer('created_by')->after('markt_id')->nullable();
            $table->date('billing_period_from')->after('created_by')->nullable();
            $table->date('billing_period_to')->after('billing_period_from')->nullable();
            $table->date('period_of_use_from')->after('billing_period_to')->nullable();
            $table->date('period_of_use_to')->after('period_of_use_from')->nullable();
            $table->date('utility_bill')->after('period_of_use_to')->nullable();
            $table->tinyInteger('advanced_payment_for_OS_type')->after('utility_bill')->nullable()->comment('1 net, 2 brutto');
            $table->string('cost',50)->after('advanced_payment_for_OS_type')->nullable();
            $table->text('message')->after('cost')->nullable();
            $table->string('year',10)->after('message')->nullable();
            $table->softDeletes()->after('year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_code');
            $table->integer('mdt_id');
            $table->integer('markt_id');
            $table->integer('created_by');
            $table->date('billing_period_from');
            $table->date('billing_period_to');
            $table->date('period_of_use_from');
            $table->date('period_of_use_to');
            $table->date('utility_bill');
            $table->tinyInteger('advanced_payment_for_OS_type');
            $table->string('cost');
            $table->text('message');
            $table->string('year');
            $table->softDeletes();
        });
    }
};
