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
        Schema::create('market_data', function (Blueprint $table) {
            $table->id();
            $table->integer('currency_id')->default(0);
            $table->integer('market_id')->default(0);
            $table->decimal('price', 28, 8)->default(0);
            $table->decimal('last_price', 28, 8)->default(0);
            $table->decimal('market_cap', 28, 8)->default(0);
            $table->decimal('last_market_cap', 28, 8)->default(0);
            $table->decimal('percent_change_1h', 5, 2)->default(0);
            $table->decimal('last_percent_change_1h', 5, 2)->default(0);
            $table->decimal('percent_change_24h', 5, 2)->default(0);
            $table->decimal('last_percent_change_24h', 5, 2)->default(0);
            $table->decimal('percent_change_7d', 5, 2)->default(0);
            $table->decimal('last_percent_change_7d', 5, 2)->default(0);
            $table->string('html_classes')->nullable()->comment('Price, percent changes html class indicator');
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
        Schema::dropIfExists('market_data');
    }
};
