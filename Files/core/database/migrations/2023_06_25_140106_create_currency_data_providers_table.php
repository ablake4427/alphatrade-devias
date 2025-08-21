<?php

use App\Constants\Status;
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
        Schema::create('currency_data_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('image');
            $table->string('configuration');
            $table->boolean('type')->default(Status::CRYPTO_CURRENCY)->comment(''.Status::CRYPTO_CURRENCY.'=crypto,'.Status::FIAT_CURRENCY.'=Fiat');
            $table->boolean('status')->default(Status::ENABLE)->comment('' . Status::ENABLE . '=enable,' . Status::DISABLE . '=disable');
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
        Schema::dropIfExists('currency_data_providers');
    }
};
