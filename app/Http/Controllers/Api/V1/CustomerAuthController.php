<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Traits\ApiResponse;
use App\Jobs\SendResetPasswordEmailConfirmationJob as confirmation;
use App\Jobs\SendResetPasswordEmailJob;
use App\Jobs\sendTwoFactorCode;
use App\Jobs\SuspiciousEmailAdmin;
use App\Jobs\SuspiciousEmailJob;
use App\Models\Tokens;
use App\Models\UserLoginAttempts;
use App\Models\UserOld;
use Config;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;
use Validator;

class CustomerAuthController extends Controller {
    use ApiResponse;

    public function __construct() {

    }

    public function getLangugageJson(Request $request) {

        $lang = $request->header('X-localization');

        $path = resource_path('lang/' . $lang . '/' . $lang . '.json');

        if (File::exists($path)) {
            $json = json_decode(file_get_contents($path), true);
            return $this->success($json);
        } else {
            return $this->error(trans('messages.language.error'));

        }

    }

    private function ipChecks($user, $request) {

        if (UserLoginAttempts::where('user_id', $user->id)->count() > 0) {
            $last_ip = UserLoginAttempts::where('user_id', $user->id)->latest('created_at')->first()->user_ip;

            if ($last_ip != $request->ip()) {

                $details['name'] = $user->name;

                $details['email'] = $user->email;

                $code = UserLoginAttempts::where('user_id', $user->id)->latest('created_at')->first()->code;

                $details['url'] = \Config::get('front.fronturl') . "/suspicious/alert/" . $code;

                dispatch(new SuspiciousEmailJob($details));

            }
        }

        $code = Str::random(20) . $user->id . strtotime("now");

        UserLoginAttempts::create(['code' => $code, 'user_id' => $user->id, 'user_ip' => $request->ip()]);

    }
    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function login(Request $request) {

        $messages = array(
            'email.required' => trans('messages.email.required'),
            'email.exists' => trans('messages.email.exists'),
            'password.required' => trans('messages.password.required'),
            'password.min' => trans('messages.password.min', ['min' => 8]),
        );

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email:filter',
                'password' => 'required',
            ], $messages);

        if (UserOld::where('email', $request->email)->count() > 0) {

            $credentials = $request->only('email', 'password');

            $token = Auth::guard('customer')->attempt($request->only(['email', 'password']));

            if (!$token) {

                return $this->error(["error" => [trans('messages.login.error')]]);
            }

            $user = Auth::guard('customer')->user();

            if ($user->is_two_factor == false) {

                //$this->ipChecks($user,$request);

                $token = $user->createToken('treaty-token')->accessToken;

                $matchThese = ['user_id' => $user->id];
                Tokens::updateOrCreate($matchThese, ['access_token' => $token]);

                return $this->success(["user" => $user, 'token' => $token, 'two_factor' => false]);

            } else {

                $details['name'] = $user->name;
                $details['email'] = $user->email;
                $details['lang'] = app()->getLocale();
                $details['code'] = mt_rand(100000, 999999);

                $old = UserOld::where('id', $user->id)->first();

                $old->twofactor_code = $details['code'];
                $old->code_expire_time = strtotime("now") + 300;
                $old->save();
                dispatch(new sendTwoFactorCode($details));
                return $this->success(["two_factor" => true]);
            }

        } else {

            return $this->error(["error" => [trans('messages.login.error')]]);

        }

    }

    public function loginTwoFactor(Request $request) {

        $messages = array(
            'email.required' => trans('messages.email.required'),
            'email.exists' => trans('messages.email.exists'),
            'email.email' => trans('messages.email.email'),
            'password.required' => trans('messages.password.required'),
            'password.min' => trans('messages.password.min', ['min' => 8]),
            'otp.required' => trans('messages.auth_code.required'),
        );

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email:filter',
                'password' => 'required|min:8',
                'otp' => 'required',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        if (Auth::guard('customer')->attempt(['twofactor_code' => $request->otp, 'email' => $request->email, 'password' => $request->password])) {

            $user = Auth::guard('customer')->user();

            //$this->ipChecks($user,$request);

            if (strtotime("now") > $user->code_expire_time) {
                return $this->error(['error' => [trans('messages.auth_code_expire')]]);
            }

            $old = UserOld::where('id', $user->id)->first();

            $old->twofactor_code = null;
            $old->code_expire_time = null;
            $old->save();
            $token = $user->createToken('treaty-token')->accessToken;

            $matchThese = ['user_id' => $user->id];
            Tokens::updateOrCreate($matchThese, ['access_token' => $token]);

            return $this->success(["user" => $user, "token" => $token, 'two_factor' => false]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]]);
        }
    }

    public function getCustomerDetail(Request $request) {

        if (Tokens::where(['access_token' => $request->bearerToken()])->count()) {
            $user = Tokens::where(['access_token' => $request->bearerToken()])->first();

            $userData = UserOld::where('id', $user->user_id)->first();

            return $this->success(["user" => $userData]);

        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]]);

        }

    }

    /**
     * Update password link needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function updatePassword(Request $request) {

        $messages = array(
            'token.required' => trans('messages.token.required'),
            'token.exists' => trans('messages.token.exists'),
            'new_password.required' => trans('messages.new_password.required'),
            'new_password.min' => trans('messages.new_password.min', ['min' => 8]),
            'confirm_password.required' => trans('messages.confirm_password.required'),
            'confirm_password.same' => trans('messages.confirm_password.same'),

        );

        $validator = Validator::make($request->all(),
            [
                'token' => 'required',
                'newPassword' => 'required|min:8',
                'confirmPassword' => 'required|same:newPassword',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        if (UserOld::where('remember_token', $request->token)->count() > 0) {

            $user = UserOld::where('remember_token', $request->token)->first(); // get user according to token
            $user->password = bcrypt($request->newPassword); // set new password
            $user->remember_token = null; // ser remember token null
            $user->save(); //user save

            $details['email'] = $user->email;
            $details['name'] = $user->first_name . " " . $user->last_name;
            $details['frontend_url'] = \Config::get('front.fronturl');

            dispatch(new confirmation($details));

            return $this->success(['message' => ['Das Passwort wurde erfolgreich aktualisiert.']]);

        } else {

            return $this->error(["error" => ["Dies ist ein ungültiges Token."]]);

        }

    }

    public function suspiciousMailSend(Request $request) {

        $messages = array(
            'token.required' => trans('messages.token.required'),
            'token.exists' => trans('messages.token.exists'),
        );

        $validator = Validator::make($request->all(),
            [
                'token' => 'required',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors(), 404);
        }

        if (UserLoginAttempts::where(['email_sent' => 0, 'code' => $request->token])->count() > 0) {

            $user_id = UserLoginAttempts::where(['email_sent' => 0, 'code' => $request->token])->first()->user_id;

            $user = UserOld::where('id', $user_id)->first();

            $details['email'] = Config::get('api.COMPANY_EMAIL');
            $details['user_email'] = $user->email;
            $details['name'] = $user->vorname . " " . $user->nachname;
            $details['id'] = $user_id;

            dispatch(new SuspiciousEmailAdmin($details));

            $attempt = UserLoginAttempts::where(['email_sent' => 0, 'code' => $request->token])->first();
            $attempt->email_sent = 1;
            $attempt->save();

            return $this->success(['message' => ['Token existiert']]);
        } else {

            return $this->error(["error" => ['Ungültiges Token']]);

        }

    }
    /**
     * Forget password link needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function forgetPassword(Request $request) {

        $messages = array(
            'email.required' => trans('messages.email.required'),
            'email.exists' => trans('messages.email.exists'),
            'email.email' => trans('messages.email.email'),
        );

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email:filter',
                'url' => 'required',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        if (UserOld::where('email', $request->email)->count() > 0) {

            $user = UserOld::where('email', $request->email)->first();
            $token = Str::random(60) . $user->id;

            $user->remember_token = $token;
            $user->save();

            $details['email'] = $user->email;
            $details['name'] = $user->first_name . " " . $user->last_name;
            $details['token'] = $token;
            $details['lang'] = app()->getLocale();
            $details['url'] = $request->url;
            $details['template'] = 'reset_password';
            dispatch(new SendResetPasswordEmailJob($details));
            return $this->success(['message' => ['Der Link zum Zurücksetzen des Passworts, der Ihnen per E-Mail zugesandt wurde.']]);

        } else {

            return $this->error(["error" => [trans('messages.login.error')]]);

        }

    }

    /**
     * update password token check link needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function resetpasswordTokenCheck(Request $request) {

        $messages = array(
            'token.required' => trans('messages.token.required'),
            'token.exists' => trans('messages.token.exists'),
        );

        $validator = Validator::make($request->all(),
            [
                'token' => 'required',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors(), 404);
        }

        if (UserOld::where('remember_token', $request->token)->count() > 0) {

            return $this->success(['message' => ['Token existiert']]);
        } else {

            return $this->error(["error" => ['Ungültiges Token']]);

        }

    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public function logout() {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh() {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
        ]);
    }

}
?>