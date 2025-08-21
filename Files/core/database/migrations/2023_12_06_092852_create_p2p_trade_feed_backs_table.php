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
        Schema::create('p2p_trade_feed_backs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('provide_by')->unsigned()->default(0);
            $table->boolean('type')->default(0)->comment("1=Positive,0=Negative");
            $table->integer('trade_id')->unsigned()->default(0);
            $table->text('message')->nullable();
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
        Schema::dropIfExists('p2p_trade_feed_backs');
    }
};
