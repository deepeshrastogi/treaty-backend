<?php
namespace App\Repositories\Users;
use App\Http\Traits\ApiResponse;
use App\Models\Tokens;
use App\Models\UserLoginAttempts;
use App\Models\UserOld;
use App\Repositories\Interfaces\Users\OldDbUserRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class OldDbUserRepository implements OldDbUserRepositoryInterface {

    use ApiResponse;

    /**
     * fetch User count.
     * @param string $email
     *  @return int $useremailCount
     */
    public function fetchUserCountByEmail($email) {

        $emailCount = UserOld::where('email', $email)->count();

        return $emailCount;

    }

    /**
     * fetch User count.
     * @param string $email
     *  @return array $user
     */
    public function fetchUserByEmail($email) {

        $user = UserOld::where('email', $email)->first();

        return $user;

    }

    /**
     * fetch User count.
     * @param string $token
     *  @return array $user
     */
    public function fetchUserCountByRememberToken($token) {

        $user = UserOld::where('remember_token', $token)->count();

        return $user;

    }

    /**
     * fetch User count.
     * @param string $token
     *  @return array $user
     */
    public function fetchUserByRememberToken($token) {

        $user = UserOld::where('remember_token', $token)->first();

        return $user;

    }

    /**
     * fetch User count.
     * @param string $email
     *  @return array $user
     */
    public function fetchUserById($userId) {

        $user = UserOld::where('id', $userId)->first();

        return $user;

    }

    /**
     * fetch Auth Guard.
     * @param object $data
     *  @return string $token
     */
    public function fetchAuthGuard($data) {

        $token = Auth::guard('customer')
            ->attempt($data->only(['email', 'password']));
        return $token;

    }

    /**
     * fetch Auth User.
     * @param empty
     *  @return string $token
     */
    public function fetchAuthUser() {

        $user = Auth::guard('customer')->user();
        return $user;

    }

    /** This is creating auth user for logged in user
     * @param int, $userId
     * @param string, $token
     *  @return empty
     */
    public function createToken($userId, $user) {

        $matchThese = ['user_id' => $userId];
        $token = $user->createToken('treaty-token')->accessToken;
        Tokens::updateOrCreate($matchThese, ['access_token' => $token]);
        return $token;

    }

    /** This function  User to older db
     * @param int, $userId
     * @param string, $token
     *  @return empty
     */
    public function saveUser($user, $details) {

        $user->twofactor_code = isset($details['code']) ? $details['code'] : null;
        $user->code_expire_time = isset($details['code_expiry_time']) ? $details['code_expiry_time'] : null;
        $user->remember_token = isset($details['remember_token']) ? $details['remember_token'] : "";
        if (isset($details['password']) && !empty($details['password'])) {

            $user->password = $details['password'];
        }
        $user->save();

    }
    /** This function finds user by using credentials and otp
     * @param obj, $data
     *  @return array, $user
     */
    public function fetchUserUsingCredentialsAndOtp($data) {

        $user = Auth::guard('customer')->attempt([
            'twofactor_code' => $data->otp,
            'email' => $data->email,
            'password' => $data->password,
        ]);

        return $user;
    }

    /** This function check token exists
     * @param string, $bearerToken
     *  @return string, $tokenCount
     */

    public function checkIfProfileTokenCount($bearerToken) {

        $tokenCount = Tokens::where(['access_token' => $bearerToken])
            ->count();

        return $tokenCount;

    }

    /** This function finds profile details by token
     * @param string, $bearerToken
     *  @return array, $user
     */

    public function profileDetailByToken($bearerToken) {

        $user = Tokens::where(['access_token' => $bearerToken])
            ->first();
        return $user;
    }

    /**
     * Profile user using user id.
     * @param int $userId
     *  @return array $user
     */
    public function profileDetailById($userId) {

        $user = UserOld::where('id', $userId)->first();

        return $user;

    }

    /**
     * Profile user using user id.
     * @param int $userId
     *  @return array $user
     */
    public function userLoginAttemptCountByToken($code) {

        $userLoginAttemptCount = UserLoginAttempts::where(['email_sent' => 0, 'code' => $code])
            ->count();

        return $userLoginAttemptCount;

    }

    /**
     * Find user attempt by login.
     * @param string $token
     *  @return array $userLoginAttempts
     */
    public function userLoginAttemptByToken($token) {

        $userLoginAttempts = UserLoginAttempts::where(['email_sent' => 0, 'code' => $token])
            ->first();

        return $userLoginAttempts;

    }
    /**
     * update login attempts
     * @param obj $userLoginAttempts
     *  @return empty
     */
    public function updateLoginAttempt($userLoginAttempts) {

        $userLoginAttempts->email_sent = 1;
        $userLoginAttempts->save();
    }

}