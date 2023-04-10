<?php

namespace App\Http\Traits;

use App\Models\Tokens;
use App\Models\UserOld;

trait ApiResponse {

    protected function success($content, $status = 200) {
        return response(['error' => null, 'content' => $content], $status);
    }

    protected function error($error, $status = 200) {
        return response(['error' => $error, 'content' => null], $status);
    }

    protected function checkToken($token) {
        if (Tokens::where(['access_token' => $token])->count()) {
            $user = Tokens::where(['access_token' => $token])->first();
            $userData = UserOld::with('userMdt')->where('id', $user->user_id)->first();
            return $userData;
        } else {
            return false;
        }
    }
}
