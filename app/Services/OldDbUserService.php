<?php

namespace App\Services;

use App\Http\Traits\ApiResponse;
use App\Jobs\SendResetPasswordEmailJob;
use App\Jobs\sendTwoFactorCode;
use App\Jobs\SuspiciousEmailAdmin;
use App\Repositories\Interfaces\Users\OldDbUserRepositoryInterface;
use Config;
use Illuminate\Support\Facades\Auth;
use Str;
use Validator;

class OldDbUserService {

    use ApiResponse;
    /**
     * @var $userRepository
     */
    protected $userRepository;

    /**
     * order constructor.
     *
     * @param Repository $userRepository
     */

    public function __construct(OldDbUserRepositoryInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function login($data) {
        $messages = [
            'email.required' => trans('messages.email.required'),
            'email.exists' => trans('messages.email.exists'),
            'password.required' => trans('messages.password.required'),
            'password.min' => trans('messages.password.min', ['min' => 8]),
        ];

        Validator::make($data->all(), [
            'email' => 'required|email:filter',
            'password' => 'required',
        ], $messages);

        if ($this->userRepository->fetchUserCountByEmail($data->email) > 0) {

            $token = $this->userRepository->fetchAuthGuard($data);
            if (!$token) {
                return $this->error(["error" => [trans('messages.login.error')]]);
            }
            $user = $this->userRepository->fetchAuthUser();

            if ($user->is_two_factor == false) {
                //$this->ipChecks($user,$request);
                $token = $this->userRepository->createToken($user->id, $user);

                return $this->success(["user" => $user, 'token' => $token, 'two_factor' => false]);
            } else {
                $details['name'] = $user->name;
                $details['email'] = $user->email;
                $details['lang'] = app()->getLocale();
                $details['code'] = mt_rand(100000, 999999);
                $details['code_expiry_time'] = strtotime("now") + 300;

                $userId = $user->id;
                $oldUser = $this->userRepository->fetchUserById($userId);

                $this->userRepository->saveUser($oldUser, $details);

                dispatch(new sendTwoFactorCode($details));
                return $this->success(["two_factor" => true]);
            }
        } else {
            return $this->error(["error" => [trans('messages.login.error')]]);
        }
    }

    /**
     * Two-factor login authentication via OTP
     * @param  object, $data
     * @return [json] token object, through an error if user OTP is not valid
     */
    public function loginTwoFactor($data) {

        $messages = [
            'email.required' => trans('messages.email.required'),
            'email.exists' => trans('messages.email.exists'),
            'email.email' => trans('messages.email.email'),
            'password.required' => trans('messages.password.required'),
            'password.min' => trans('messages.password.min', ['min' => 8]),
            'otp.required' => trans('messages.auth_code.required'),
        ];

        $validator = Validator::make($data->all(), [
            'email' => 'required|email:filter',
            'password' => 'required|min:8',
            'otp' => 'required',
        ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($data->errors());
        }

        if ($this->userRepository->fetchUserUsingCredentialsAndOtp($data)
        ) {

            $user = $this->userRepository->fetchAuthUser();
            //$this->ipChecks($user,$request);
            if (strtotime("now") > $user->code_expire_time) {
                return $this->error(['error' => [trans('messages.auth_code_expire')]]);
            }

            $details['code_expiry_time'] = null;
            $details['code'] = null;

            $userId = $user->id;
            $oldUser = $this->userRepository->fetchUserById($userId);

            $this->userRepository->saveUser($oldUser, $details);

            $token = $this->userRepository->createToken($user->id, $user);

            return $this->success(["user" => $user, "token" => $token, 'two_factor' => false]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]]);
        }
    }

    /**
     * Get user details using token through get
     * @param object,  $data
     * @return [json] \Illuminate\Http\Response
     */
    public function getProfileDetail($data) {
        $bearerToken = $data->bearerToken();
        $tokenCount = $this->userRepository->checkIfProfileTokenCount($bearerToken);

        if ($tokenCount) {
            $user = $this->userRepository->profileDetailByToken($bearerToken);

            $userData = $this->userRepository->profileDetailById($user->user_id);
            return $this->success(["user" => $userData]);
        } else {
            return $this->error(['error' => [trans('messages.unauthorize')]]);
        }
    }

    /**
     * Send suspicious mail to admin through post
     * @param  object, $data
     * @return [json] \Illuminate\Http\Response
     */
    public function suspiciousMailSend($data) {
        $messages = [
            'token.required' => trans('messages.token.required'),
            'token.exists' => trans('messages.token.exists'),
        ];

        $validator = Validator::make($data->all(), [
            'token' => 'required',
        ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors(), 404);
        }

        $token = $data->token;
        if ($this->userRepository->userLoginAttemptCountByToken($token) > 0) {
            $details = [];
            $userLoginAttempts = $this->userRepository->userLoginAttemptByToken($token);
            $userId = $userLoginAttempts->user_id;
            $user = $this->userRepository->profileDetailById($userId);

            $details['email'] = Config::get('api.COMPANY_EMAIL');
            $details['user_email'] = $user->email;
            $details['name'] = $user->vorname . " " . $user->nachname;
            $details['id'] = $userId;

            $this->userRepository->updateLoginAttempt($userLoginAttempts);
            dispatch(new SuspiciousEmailAdmin($details));

            return $this->success(['message' => ['Token existiert']]);
        } else {
            return $this->error(["error" => ['Ungültiges Token']]);
        }
    }

    /**
     * Create password link needs to send through post
     * @param  object, $data
     * @return [json] \Illuminate\Http\Response
     */
    public function generatePassword($data, $emailType) {

        $messages = [
            'email.required' => trans('messages.email.required'),
            'email.exists' => trans('messages.email.exists'),
            'email.email' => trans('messages.email.email'),
        ];

        $validator = Validator::make($data->all(), [
            'email' => 'required|email:filter',
            'url' => 'required',
        ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $email = $data->email;

        if ($this->userRepository->fetchUserCountByEmail($email) > 0) {

            $details = [];
            $user = $this->userRepository->fetchUserByEmail($email);
            $userId = $user->id;

            $userDetails['remember_token'] = Str::random(60) . $userId;
            $this->userRepository->saveUser($user, $userDetails);

            $details['email'] = $user->email;
            $details['name'] = $user->first_name . " " . $user->last_name;
            $details['token'] = $userDetails['remember_token'];
            $details['lang'] = app()->getLocale();
            $details['url'] = $data->url;
            $details['template'] = $emailType;

            dispatch(new SendResetPasswordEmailJob($details));
            return $this->success(['message' => ['Der Link zum Zurücksetzen des Passworts, der Ihnen per E-Mail zugesandt wurde.']]);
        } else {
            return $this->error(["error" => [trans('messages.login.error')]]);
        }
    }

    /**
     * update password token check
     * @param  object , $data
     * @return [json] \Illuminate\Http\Response
     */
    public function resetPasswordTokenCheck($data) {
        $messages = [
            'token.required' => trans('messages.token.required'),
            'token.exists' => trans('messages.token.exists'),
        ];

        $validator = Validator::make($data->all(), [
            'token' => 'required',
        ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors(), 404);
        }

        if ($this->userRepository->fetchUserCountByRememberToken($data->token) > 0) {
            return $this->success(['message' => ['Token existiert']]);
        } else {
            return $this->error(["error" => ['Ungültiges Token']]);
        }
    }
    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function logout() {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => trans('messages.user_logout'),
        ]);
    }

    /**
     * Refersh the token through get
     * @return [json] \Illuminate\Http\Response
     */
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

    /**
     * Update password link needs to send through post
     * @param object, $data
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function updatePassword($data) {

        $messages = [
            'token.required' => trans('messages.token.required'),
            'token.exists' => trans('messages.token.exists'),
            'new_password.required' => trans('messages.new_password.required'),
            'new_password.min' => trans('messages.new_password.min', ['min' => 8]),
            'confirm_password.required' => trans('messages.confirm_password.required'),
            'confirm_password.same' => trans('messages.confirm_password.same'),
        ];

        $validator = Validator::make($data->all(), [
            'token' => 'required',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
        ], $messages);

        //if validation failes, then  error would return
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }

        $token = $data->token;

        if ($this->userRepository->fetchUserCountByRememberToken($token) > 0) {

            $user = $this->userRepository->fetchUserByRememberToken($token);
            $details['password'] = bcrypt($data->newPassword);
            $this->userRepository->saveUser($user, $details);

            return $this->success(['message' => ['Das Passwort wurde erfolgreich aktualisiert']]);
        } else {
            return $this->error(["error" => ["Dies ist ein ungültiges Token"]]);
        }
    }

}
