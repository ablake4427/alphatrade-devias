<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetCodeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $fieldType = $this->findFieldType();
        $user      = User::where($fieldType, $request->value)->first();

        if (!$user) {
            $notify[] = 'The account could not be found';
            return responseError('validation_error', $notify);
        }

        PasswordReset::where('email', $user->email)->delete();
        $code                 = verificationCode(6);
        $password             = new PasswordReset();
        $password->email      = $user->email;
        $password->token      = $code;
        $password->created_at = Carbon::now();
        $password->save();

        $userIpInfo      = getIpInfo();
        $userBrowserInfo = osBrowser();
        notify($user, 'PASS_RESET_CODE', [
            'code'             => $code,
            'operating_system' => @$userBrowserInfo['os_platform'],
            'browser'          => @$userBrowserInfo['browser'],
            'ip'               => @$userIpInfo['ip'],
            'time'             => @$userIpInfo['time'],
        ], ['email']);

        $email      = $user->email;
        $response[] = 'Verification code sent to mail';
        return responseSuccess('code_sent', $response, ['email' => $email]);
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'  => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }
        $code = $request->code;

        if (PasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            $notify[] = 'Verification code doesn\'t match';
            return responseError('validation_error', $notify);
        }

        $response[] = 'You can change your password.';
        return responseSuccess('verified', $response);        
    }

    public function reset(Request $request)
    {

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors()->all());
        }

        $reset = PasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        if (!$reset) {
            $response[] = 'Invalid verification code';
            return responseError('validation_error', $response);            
        }

        $user           = User::where('email', $reset->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        $userIpInfo  = getIpInfo();
        $userBrowser = osBrowser();
        notify($user, 'PASS_RESET_DONE', [
            'operating_system' => @$userBrowser['os_platform'],
            'browser'          => @$userBrowser['browser'],
            'ip'               => @$userIpInfo['ip'],
            'time'             => @$userIpInfo['time'],
        ], ['email']);

        $response[] = 'Password changed successfully';
        return responseSuccess('password_changed', $response);
    }

    protected function rules()
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        return [
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', $passwordValidation],
        ];
    }

    private function findFieldType()
    {
        $input = request()->input('value');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $input]);
        return $fieldType;
    }
}
