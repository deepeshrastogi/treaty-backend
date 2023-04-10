<?php
namespace App\Http\Controllers\Api\V2\Users;

use App\Http\Controllers\Api\V2\Controller;
use App\Http\Traits\ApiResponse;
use App\Services\OldDbUserService;
use Illuminate\Http\Request;

class UserAuthController extends Controller {

    use ApiResponse;

    /**
     * @var OldDbUserService
     */
    protected $userService;

    /**
     * PostController Constructor
     *
     * @param OldDbUserService $OldDbCustomerService
     *
     */
    public function __construct(OldDbUserService $userService) {
        $this->userService = $userService;
    }

    /**
     * Login user and create token, email and password needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function login(Request $request) {
        return $this->userService->login($request);
    }

    /**
     * Two-factor login authentication via OTP
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user OTP is not valid
     */
    public function loginTwoFactor(Request $request) {
        return $this->userService->loginTwoFactor($request);
    }

    /**
     * Get customer details
     * @param  \Illuminate\Http\Request
     * @return [json] \Illuminate\Http\Response
     */
    public function getProfileDetail(Request $request) {

        return $this->userService->getProfileDetail($request);
    }

    /**
     * Send suspicious mail to admin through post
     * @param  \Illuminate\Http\Request
     * @return [json] \Illuminate\Http\Response
     */
    public function suspiciousMailSend(Request $request) {

        return $this->userService->suspiciousMailSend($request);

    }

    /**
     * create Password
     * @param  \Illuminate\Http\Request
     * @return [json] \Illuminate\Http\Response
     */

    public function createPassword(Request $request) {

        return $this->userService->generatePassword($request, 'create_password');

    }

    /**
     * Forget password link needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] \Illuminate\Http\Response
     */
    public function forgetPassword(Request $request) {
        return $this->userService->generatePassword($request, 'reset_password');
    }

    /**
     * update password token check through post
     * @param  \Illuminate\Http\Request
     * @return [json] \Illuminate\Http\Response
     */
    public function resetPasswordTokenCheck(Request $request) {
        return $this->userService->resetPasswordTokenCheck($request);
    }

    /**
     * Logout through get
     * @return [json] \Illuminate\Http\Response
     */
    public function logout() {

        return $this->userService->logout();

    }

    /**
     * Refersh the token through get
     * @return [json] \Illuminate\Http\Response
     */
    public function refresh() {
        return $this->userService->logout();
    }

    /**
     * Update password link needs to send through post
     * @param  \Illuminate\Http\Request
     * @return [json] token object, through an error if user credentials are not valid
     */
    public function updatePassword(Request $request) {

        return $this->userService->updatePassword($request);

    }
}
