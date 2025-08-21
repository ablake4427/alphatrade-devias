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
        Schema::create('p2p_ads', function (Blueprint $table) {
            $table->id();
            $table->boolean("type")->comment("1=sell,2=buy")->default(1);
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('coin_id')->unsigned()->default(0);
            $table->integer('currency_id')->unsigned()->default(0);
            $table->integer('payment_window_id')->unsigned()->default(0);
            $table->boolean("price_type")->comment("1=Price type fixed,2=price type margin")->default(1);
            $table->decimal('price', 28, 8)->default(0);
            $table->decimal('price_margin', 5, 2)->default(0);
            $table->decimal('minimum_amount', 28, 8)->default(0);
            $table->decimal('maximum_amount', 28, 8)->default(0);
            $table->longText('payment_details')->nullable();
            $table->longText('terms_of_trade')->nullable();
            $table->longText('auto_replay_text')->nullable();
            $table->boolean("status")->comment("0=Pending,1=completed,9=reject")->default(0);
            $table->boolean('complete_step')->default(0);
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
        Schema::dropIfExists('p2p_ads');
    }
};
