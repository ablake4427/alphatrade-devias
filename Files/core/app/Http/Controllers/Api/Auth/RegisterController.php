<?php

namespace App\Http\Controllers\Api\Auth;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
     */

    use RegistersUsers;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        $passwordValidation = Password::min(6);

        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $agree = 'nullable';

        if (gs('agree')) {
            $agree = 'required';
        }

        $validate = Validator::make($data, [
            'firstname' => 'required',
            'lastname'  => 'required',
            'email'     => 'required|string|email|unique:users',
            'password'  => ['required', 'confirmed', $passwordValidation],
            'agree'     => $agree,
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required'  => 'The last name field is required',
        ]);

        return $validate;
    }

    public function register(Request $request) {
        if (!gs('registration')) {
            $notify[] = 'Registration not allowed';
            return responseError('validation_error', $notify);            
        }
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
           return responseError('validation_error', $validator->errors()->all());
        }

        if ($request->is_web) {
            $request->{'g-recaptcha-response'} = $request->recaptcha;
            if(!verifyCaptcha()){
                $notify[] = 'Invalid captcha provided';
                return responseError('captcha_error', $notify);                
            }
        }

        $user = $this->create($request->all());

        Auth::login($user);
        createWallet();

        $response['access_token'] = $user->createToken('auth_token')->plainTextToken;
        $response['user']         = $user;
        $response['token_type']   = 'Bearer';
        $notify[] = 'Registration successful';
        return responseSuccess('registration_success', $notify, $response);        
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data) {
        $referBy = @$data['reference'];
        if ($referBy) {
            $referUser = User::where('username', $referBy)->first();
        } else {
            $referUser = null;
        }
        //User Create
        $user            = new User();
        $user->firstname = $data['firstname'];
        $user->lastname  = $data['lastname'];
        $user->email     = strtolower($data['email']);
        $user->password  = Hash::make($data['password']);
        $user->ref_by    = $referUser ? $referUser->id : 0;
        $user->kv        = gs('kv') ? Status::UNVERIFIED : Status::VERIFIED;
        $user->ev        = gs('ev') ? Status::UNVERIFIED : Status::VERIFIED;
        $user->sv        = gs('sv') ? Status::UNVERIFIED : Status::VERIFIED;
        $user->ts        = Status::DISABLE;
        $user->tv        = Status::VERIFIED;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail', $user->id);
        $adminNotification->save();

        //Login Log Create
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

        $user = User::find($user->id);

        return $user;
    }
}
