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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->boolean('type')->default(1)->comment('1=Crypto,2=Fiat');
            $table->string('name')->unique()->nullable();
            $table->string('symbol')->unique()->nullable();
            $table->string('image')->nullable();
            $table->integer('rank')->default(0);
            $table->boolean('status')->default(1)->comment('1=Enable,0=Disable');
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
        Schema::dropIfExists('currencies');
    }
};
