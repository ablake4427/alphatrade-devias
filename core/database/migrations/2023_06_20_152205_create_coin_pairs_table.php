<?php

use App\Constants\Status;
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
        Schema::create('coin_pairs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('full_name')->nullable();
            $table->integer('market_id')->default(0);
            $table->integer('coin_id')->default(0);
            $table->decimal('price', 28, 8)->default(0)->comment("1 Coin n Market Coin");
            $table->decimal('minimum_amount', 28, 8)->default(0);
            $table->decimal('maximum_amount', 28, 8)->default(0);
            $table->decimal('fixed_charge_for_sale', 28, 8)->default(0);
            $table->decimal('percent_charge_for_sale', 5, 2)->default(0);
            $table->decimal('fixed_charge_for_buy', 28, 8)->default(0);
            $table->decimal('percent_charge_for_buy', 5, 2)->default(0);
            $table->boolean('status')->default(Status::ENABLE)->comment(''.Status::ENABLE.'=enable,'.Status::DISABLE.'=disable');
            $table->boolean('is_default')->default(Status::NO)->comment(''.Status::YES.'=yes,'.Status::NO.'=no');
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
        Schema::dropIfExists('coin_pairs');
    }
};
