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
        Schema::create('p2p_trade_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('trade_id')->unsigned()->default(0);
            $table->integer('sender_id')->unsigned()->default(0);
            $table->integer('receiver_id')->unsigned()->default(0);
            $table->integer('admin_id')->unsigned()->default(0);
            $table->longText('message')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('p2p_trade_messages');
    }
};
