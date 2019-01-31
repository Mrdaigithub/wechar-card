<?php
/**
 * Created by PhpStorm.
 * User: mrdai
 * Date: 2018-12-24
 * Time: 20:27
 */

namespace App\Helpers\Api;

use Symfony\Component\HttpFoundation\Response;

trait ApiResponse {
  
  private $message = "请求成功";
  
  private $data = NULL;
  
  private $code = Response::HTTP_OK;
  
  private $statusCode = Response::HTTP_OK;
  
  private $header = [];
  
  /**
   * ApiResponse constructor.
   *
   * @param string $message
   * @param null $data
   * @param int $code
   * @param int $statusCode
   */
  public function __construct(
    string $message = "",
    $data = NULL,
    int $code = Response::HTTP_OK,
    int $statusCode = Response::HTTP_OK
  ) {
    $this->message    = $message;
    $this->data       = $data;
    $this->code       = $code;
    $this->statusCode = $statusCode;
  }
  
  /**
   * @return string
   */
  public function getMessage(): string {
    return $this->message;
  }
  
  /**
   * @param string $message
   */
  public function setMessage(string $message): void {
    $this->message = $message;
  }
  
  /**
   * @return null
   */
  public function getData() {
    return $this->data;
  }
  
  /**
   * @param null $data
   */
  public function setData($data): void {
    $this->data = $data;
  }
  
  /**
   * @return int
   */
  public function getCode(): int {
    return $this->code;
  }
  
  /**
   * @param int $code
   */
  public function setCode(int $code): void {
    $this->code = $code;
  }
  
  /**
   * @return int
   */
  public function getStatusCode(): int {
    return $this->statusCode;
  }
  
  /**
   * @param int $statusCode
   */
  public function setStatusCode(int $statusCode): void {
    $this->statusCode = $statusCode;
  }
  
  /**
   * @return array
   */
  public function getHeader(): array {
    return $this->header;
  }
  
  /**
   * @param array $header
   */
  public function setHeader(array $header): void {
    $this->header = $header;
  }
  
  
  /**
   * @param        $data
   * @param string $message
   *
   * @return mixed
   */
  public function success($data = NULL, $message = "请求成功") {
    $this->setData($data);
    $this->setMessage($message);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
  
  /**
   * @param        $data
   * @param string $message
   *
   * @return mixed
   */
  public function created($data = NULL, $message = "创建成功") {
    $this->setData($data);
    $this->setMessage($message);
    $this->setCode(Response::HTTP_CREATED);
    $this->setStatusCode(Response::HTTP_CREATED);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
  
  /**
   * @param null $data
   * @param string $message
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function badRequest($data = NULL, $message = "错误的请求") {
    $this->setData($data);
    $this->setMessage($message);
    $this->setCode(Response::HTTP_BAD_REQUEST);
    $this->setStatusCode(Response::HTTP_BAD_REQUEST);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
  
  /**
   * @param null $data
   * @param string $message
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function unauthorized($data = NULL, $message = "无效的用户身份") {
    $this->setData($data);
    $this->setMessage($message);
    $this->setCode(Response::HTTP_UNAUTHORIZED);
    $this->setStatusCode(Response::HTTP_UNAUTHORIZED);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
  
  /**
   * @param null $data
   * @param string $message
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function forbidden($data = NULL, $message = "无权限") {
    $this->setData($data);
    $this->setMessage($message);
    $this->setCode(Response::HTTP_FORBIDDEN);
    $this->setStatusCode(Response::HTTP_FORBIDDEN);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
  
  /**
   * @param null $data
   * @param string $message
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function notFound($data = NULL, $message = "找不到资源") {
    $this->setData($data);
    $this->setMessage($message);
    $this->setCode(Response::HTTP_NOT_FOUND);
    $this->setStatusCode(Response::HTTP_NOT_FOUND);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
  
  /**
   * @param null $data
   * @param string $message
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function methodNotAllowed($data = NULL, $message = "无效的请求方法") {
    $this->setData($data);
    $this->setMessage($message);
    $this->setCode(Response::HTTP_METHOD_NOT_ALLOWED);
    $this->setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
  
  /**
   * @param null $data
   * @param string $message
   *
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   */
  public function internalServerError($data = NULL, $message = "服务器错误") {
    $this->setData($data);
    $this->setMessage($message);
    $this->setCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    
    return response()->json([
      "code"    => $this->getCode(),
      "message" => $this->getMessage(),
      "data"    => $this->getData(),
    ], $this->getStatusCode())->withHeaders($this->getHeader());
  }
}
