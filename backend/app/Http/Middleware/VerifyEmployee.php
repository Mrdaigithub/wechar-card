<?php

/**
 * 验证员工老板身份
 */

namespace App\Http\Middleware;

use App\Helpers\Api\ApiResponse;
use App\Utils\ResponseMessage;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyEmployee {
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

        $identity = JWTAuth::parseToken()->authenticate()->identity;
        if ($identity !== 1 || $identity !== 2) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }

        return $next($request);
    }
}
