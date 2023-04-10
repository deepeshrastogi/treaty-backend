<?php
namespace App\Repositories\Interfaces\Users;
/*
 * Interface OldDbOrderRepositoryInterface
 * @package App\Repositories
 */
Interface OldDbUserRepositoryInterface {

    public function createToken($userId, $user);
    public function checkIfProfileTokenCount($bearerToken);
    public function fetchUserCountByEmail($email);
    public function fetchAuthGuard($data);
    public function fetchAuthUser();
    public function fetchUserByEmail($email);
    public function fetchUserById($userId);
    public function fetchUserCountByRememberToken($token);
    public function fetchUserByRememberToken($token);
    public function fetchUserUsingCredentialsAndOtp($data);
    public function profileDetailByToken($bearerToken);
    public function profileDetailById($userId);
    public function saveUser($user, $details);
    public function userLoginAttemptCountByToken($token);
    public function userLoginAttemptByToken($token);
    public function updateLoginAttempt($userLoginAttempts);

}
?>