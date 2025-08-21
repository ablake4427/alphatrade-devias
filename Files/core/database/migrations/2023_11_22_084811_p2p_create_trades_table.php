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
        Schema::create('p2p_trades', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->boolean('type')->default(0)->comment("1=buy,2=sell");
            $table->integer('ad_id')->unsigned()->default(0);
            $table->integer('buyer_id')->unsigned()->default(0);
            $table->integer('seller_id')->unsigned()->default(0);
            $table->integer('payment_method_id')->unsigned()->default(0);
            $table->decimal('asset_amount', 28, 8)->default(0);
            $table->decimal('fiat_amount', 28, 8)->default(0);
            $table->decimal('price', 28, 8)->default(0);
            $table->boolean('status')->default(0)->comment("0=pending,1=completed,2=please relase,3=Reported,9=cancel");
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
        Schema::dropIfExists('p2p_trades');
    }
};
