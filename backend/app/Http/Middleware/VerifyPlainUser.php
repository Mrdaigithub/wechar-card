<?php

/**
 * 验证员工普通用户身份
 */

namespace App\Http\Middleware;

use App\Helpers\Api\ApiResponse;
use App\Utils\ResponseMessage;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyPlainUser {
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (JWTAuth::parseToken()->authenticate()->identity !== 0) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }

        return $next($request);
    }
}
