<?php

namespace App\Exceptions;

use App\Helpers\Api\ApiResponse;
use App\Utils\ResponseMessage;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler {

    use ApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport
        = [//
        ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash
        = [
            'password',
            'password_confirmation',
        ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     *
     * @return void
     * @throws \Exception
     */
    public function report(Exception $exception) {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception) {
        return $this->handle($request, $exception);
    }

    public function handle($request, Exception $e) {
        // 处理jwt token 过期异常
        if ($e instanceof TokenExpiredException) {
            return $this->unauthorized(NULL, ResponseMessage::$message[401002]);
        }
        // 处理The token has been blacklisted
        if ($e instanceof TokenBlacklistedException) {
            return $this->unauthorized(NULL, ResponseMessage::$message[401003]);
        }
        // 处理jwt token 未通过验证异常
        if ($e instanceof JWTException) {
            return $this->unauthorized(NULL, ResponseMessage::$message[401001]);
        }
        // 无效的请求方法
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->methodNotAllowed();
        }
        // 找不到资源
        if ($e instanceof NotFoundHttpException) {
            return $this->notFound();
        }

        return parent::render($request, $e);
    }

    /**
     * 处理接口参数错误异常
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Illuminate\Validation\ValidationException $exception
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    protected function invalid($request, ValidationException $exception) {
        $errors = $exception->errors();
        $res    = [];
        foreach ($errors as $item) {
            array_push($res, $item[0]);
        }

        return $this->badRequest(NULL, implode(", ", $res));
    }

    /**
     * 处理接口参数错误异常
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Illuminate\Validation\ValidationException $exception
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    protected function invalidJson($request, ValidationException $exception) {
        $errors = $exception->errors();
        $res    = [];
        foreach ($errors as $item) {
            array_push($res, $item[0]);
        }

        return $this->badRequest(NULL, implode(", ", $res));
    }
}
