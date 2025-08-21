<?php

namespace App\Http\Controllers\Api\Auth\Web3;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Elliptic\EC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use kornrunner\Keccak;

class MetamaskController extends Controller
{
    public function message(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wallet_address' => 'required',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $nonce   = strtoupper(getTrx());
        $message = gs('site_name') . " wants you to sign in with your Ethereum account " . $request->wallet_address . ". By sign in i'am agree with " . gs('site_name') . " privacy & policy. \n\nNonce: " . $nonce . "\nIssued At: " . now();

        $notify[] = 'Web3 message';
        return responseSuccess('web3_message', $notify, [
            'wallet'  => $request->wallet_address,
            'nonce'   => $nonce,
            'message' => $message,
        ]);

    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'signature' => 'required',
            'message'   => 'required',
            'wallet'    => 'required',
            'nonce'     => 'required',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $result = $this->verifySignature($request->message, $request->signature, $request->wallet);

        if (!$result) {
            $notify[] = 'Something went to the wrong';
            return responseError('something_wrong', $notify);
        }

        $user    = User::where('metamask_wallet_address', $request->wallet)->first();
        $newUser = false;

        if (!$user) {
            $user                          = new User();
            $user->username                = $request->wallet;
            $user->metamask_wallet_address = $request->wallet;
            $user->metamask_nonce          = $request->nonce;
            $user->kv                      = Status::YES;
            $user->ev                      = Status::YES;
            $user->sv                      = Status::YES;
            $user->ts                      = 0;
            $user->tv                      = 1;
            $user->save();

            $adminNotification            = new AdminNotification();
            $adminNotification->user_id   = $user->id;
            $adminNotification->title     = 'New member registered';
            $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
            $adminNotification->save();

            $newUser = true;
        }

        Auth::login($user);
        if ($newUser) {
            createWallet();
        }

        $ip        = getRealIP();
        $exist     = UserLogin::where('user_ip', $ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent          = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();

        $tokenResult = $user->createToken('auth_token')->plainTextToken;
        $response[]  = 'Login Successful';

        createWallet();

        return responseSuccess('login_success', $response, [
            'user'         => auth()->user(),
            'access_token' => $tokenResult,
            'token_type'   => 'Bearer',
        ]);
    }

    protected function verifySignature(string $message, string $signature, string $address): bool
    {
        $hash = Keccak::hash(sprintf("\x19Ethereum Signed Message:\n%s%s", strlen($message), $message), 256);
        $sign = [
            'r' => substr($signature, 2, 64),
            's' => substr($signature, 66, 64),
        ];
        $recid = ord(hex2bin(substr($signature, 130, 2))) - 27;

        if ($recid != ($recid & 1)) {
            return false;
        }

        $pubkey          = (new EC('secp256k1'))->recoverPubKey($hash, $sign, $recid);
        $derived_address = '0x' . substr(Keccak::hash(substr(hex2bin($pubkey->encode('hex')), 1), 256), 24);
        return (Str::lower($address) === $derived_address);
    }
}
