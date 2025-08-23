<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('pair_id')->unsigned()->default(0);
            $table->boolean('order_side')->comment('1=buy,2=sell');
            $table->decimal('rate',28,8)->default(0)->comment('user providing rate');
            $table->decimal('price',28,8)->default(0)->comment('coin price');
            $table->decimal('amount',28,8)->default(0)->comment('coin quantity');
            $table->decimal('total',28,8)->default(0);
            $table->decimal('charge',28,8)->default(0);
            $table->boolean('status')->default(0)->comment("0=Open,1=Completed,9=canceled");
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
        Schema::dropIfExists('orders');
    }
};
