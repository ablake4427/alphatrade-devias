<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\P2P\Ad;
use App\Models\P2P\AdPaymentMethod;
use App\Models\P2P\PaymentMethod;
use App\Models\P2P\PaymentWindow;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lorem = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel ullam atque sit dicta vitae? Reprehenderit corporis, ea laboriosam obcaecati vero delectus magni laborum, sapiente laudantium exercitationem hic iusto eaque enim!";
        $minimumAmount = rand(500, 1200);

        for ($i = 0; $i <= 100; $i++) {
            $asset         = Currency::where('id',3)->crypto()->active()->inRandomOrder()->first();
            $fiat          = Currency::fiat()->active()->inRandomOrder()->first();
            $user          = User::active()->inRandomOrder()->first();

            $paymentWindow = PaymentWindow::active()->inRandomOrder()->first();
            $paymentMethod = PaymentMethod::active()->inRandomOrder()->first();

            $ad           = new Ad();
            $ad->user_id  = $user->id;
            $ad->type     = rand(1, 2);
            $ad->asset_id = $asset->id;
            $ad->fiat_id  = $fiat->id;

            $ad->payment_window_id = $paymentWindow->id;
            $ad->price_type        = 1;
            $ad->price             = @$asset->marketData->price;
            $ad->minimum_amount    = $minimumAmount;
            $ad->maximum_amount    = rand(10000, 15000);
            $ad->price_margin      = 0;

            $ad->payment_details  = $lorem;
            $ad->terms_of_trade   = $lorem;
            $ad->auto_replay_text = $lorem;
            $ad->complete_step    = 3;
            $ad->save();

            $paymentMethods = [
                'payment_method_id' => $paymentMethod->id,
                'ad_id'             => $ad->id,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];

            AdPaymentMethod::insert($paymentMethods);

            $wallet = Wallet::where('user_id', $user->id)->funding()->where('currency_id', $asset->id)->first();

            if (!$wallet) {
                $wallet               = new Wallet();
                $wallet->user_id      = $user->id;
                $wallet->currency_id  = $asset->id;
                $wallet->wallet_type  = 2;
                $wallet->balance     += $minimumAmount;
            } else {
                $wallet->balance += $minimumAmount;
            }

            $wallet->save();
        }
    }
}
