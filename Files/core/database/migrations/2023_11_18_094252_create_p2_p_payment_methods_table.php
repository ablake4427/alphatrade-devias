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
        Schema::create('p2p_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('slug')->unique()->nullable();
            $table->text('supported_currency')->nullable();
            $table->boolean('status')->default(1)->comment("1=Active,0=Inactive");
            $table->integer('form_id')->default(0)->unsigned();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('p2p_payment_methods');
    }
};
