<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Traits\ApiResponse;
use App\Jobs\SendResetPasswordEmailJob;
use App\Jobs\sendTwoFactorCode;
use App\Jobs\SuspiciousEmailJob;
use App\Models\mdt;
use App\Models\User;
use App\Models\UserLoginAttempts;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;
use Validator;

class AuthController extends Controller {
    use ApiResponse;

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['getLangugageJson', 'resetpasswordTokenCheck', 'forgetPassword', 'updatePassword', 'login', 'loginTwoFactor']]);
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

                dispatch(new SuspiciousEmailJob($details));

            }
        }

        UserLoginAttempts::create(['user_id' => $user->id, 'user_ip' => $request->ip()]);

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
                'email' => 'required|email:filter|exists:users',
                'password' => 'required',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);

        if (!$token) {
            return $this->error(["error" => [trans('messages.login.error')]]);
        }

        $user = Auth::user();
        if ($user->is_two_factor == false) {
            $this->ipChecks($user, $request);
            $token = $user->createToken('treaty-token')->accessToken;
            $user->mdt = 1;
            return $this->success(["user" => $user, 'token' => $token, 'two_factor' => false]);

        } else {

            $details['name'] = $user->name;
            $details['email'] = $user->email;
            $details['lang'] = app()->getLocale();
            $details['code'] = mt_rand(100000, 999999);
            $user = Auth::user();

            $user->twofactor_code = $details['code'];
            $user->code_expire_time = strtotime("now") + 300;
            $user->save();
            $user->mdt = 1;
            dispatch(new sendTwoFactorCode($details));
            return $this->success(["two_factor" => true]);
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
                'email' => 'required|email:filter|exists:users',
                'password' => 'required|min:8',
                'otp' => 'required',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        if (Auth::attempt(['twofactor_code' => $request->otp, 'email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            UserLoginAttempts::create(['user_id' => $user->id, 'user_ip' => $request->ip()]);

            if (strtotime("now") > $user->code_expire_time) {
                return $this->error(['error' => [trans('messages.auth_code_expire')]]);
            }

            $user->twofactor_code = null;
            $user->code_expire_time = null;
            $user->save();
            $token = $user->createToken('treaty-token')->accessToken;
            $user->mdt = 1;
            return $this->success(["user" => $user, "token" => $token, 'two_factor' => false]);
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
                'token' => 'required|exists:users,remember_token',
                'newPassword' => 'required|min:8',
                'confirmPassword' => 'required|same:newPassword',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $user = User::where('remember_token', $request->token)->first(); // get user according to token
        $user->password = bcrypt($request->newPassword); // set new password
        $user->remember_token = null; // ser remember token null
        $user->save(); //user save

        return $this->success(['message' => ['The Password updated successfully']]);
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
                'email' => 'required|email:filter|exists:users,email',
                'url' => 'required',
            ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60) . $user->id;
        $user->setRememberToken($token);
        $user->save();

        $details['email'] = $user->email;
        $details['name'] = $user->first_name . " " . $user->last_name;
        $details['token'] = $token;
        $details['lang'] = app()->getLocale();
        $details['url'] = $request->url;
        $details['template'] = 'reset_password';
        dispatch(new SendResetPasswordEmailJob($details));
        return $this->success(['message' => [trans('messages.password_reset')]]);
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

        $validator = Validator::make($request->all(),[
            'token' => 'required|exists:users,remember_token',
        ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors(), 404);
        }
        return $this->success(['message' => ['Token existiert']]);
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