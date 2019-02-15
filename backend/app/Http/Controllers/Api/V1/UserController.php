<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UpdatePlainUserRequest;
use App\Http\Requests\UpdateShopEmployeeRequest;
use App\Model\Shop;
use App\Model\User;
use App\Utils\ResponseMessage;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends ApiController {

    private $oneself;

    public function __construct() {
        $this->oneself = JWTAuth::parseToken()->authenticate();
    }

    /**
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function list() {
        if ($notAdmin = $this->isAdmin($this->oneself)) {
            return $notAdmin;
        }

        return $this->success(User::all());
    }

    /**
     * 获取普通用户列表
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function listPlainUser() {
        if ($notAdmin = $this->isAdmin($this->oneself)) {
            return $notAdmin;
        }

        $plainUserList = User::where("identity", 0)->get()->map(function($item) {
            $signInLogs = $item->signInLogs();
            if ($signInLogs->get()->isEmpty()) {
                $item->sign_in_num = 0;
            } else {
                $item->sign_in_num = collect(explode(",", $item->signInLogs()->first()->month_sign_in_log))->count();
            }

            return $item;
        });

        return $this->success($plainUserList);
    }

    /**
     * 获取所有商铺成员列表
     *
     * @return mixed
     */
    public function listShopEmployee() {
        if ($notAdmin = $this->isAdmin($this->oneself)) {
            return $notAdmin;
        }

        $plainUserList = User::where("identity", 1)->orWhere("identity", 2)->get()->map(function($item) {
            $shop = $item->shop();
            if ($shop->get()->isEmpty()) {
                $item->shop_id = NULL;
            } else {
                $item->shop_id = $shop->first()->id;
            }

            return $item;
        });

        return $this->success($plainUserList);
    }

    /**
     * 获取指定商铺成员列表
     *
     * @param $id
     *
     * @return mixed
     */
    public function listShopEmployeeByShopId($id) {
        if ($notBoss = $this->isBoss($this->oneself)) {
            return $notBoss;
        }

        $shop = Shop::find($id);
        if (!$shop) {
            return $this->badRequest(NULL, ResponseMessage::$message[400028]);
        }

        $shopId = $this->oneself->shop()->get()->isNotEmpty() ? $this->oneself->shop()->first()->id : NULL;
        if ($shopId !== $shop->id) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }

        return $this->success(
            $shop->users()->get()->map(function($item) {
                return collect($item)->except(["openid", "lottery_num", "real_name", "phone", "remarks"]);
            })
        );
    }

    /**
     * 通过 id 查找用户
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function getUserById($id) {
        if ($id == 0) {
            return $this->success(JWTAuth::parseToken()->authenticate());
        }
        $user = User::find($id);
        if (!$user) {
            return $this->notFound(NULL, ResponseMessage::$message[400007]);
        }

        return $this->success($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function save(Request $request) {
        //
    }

    /**
     * 更新普通用户信息
     *
     * @param \App\Http\Requests\UpdatePlainUserRequest $request
     * @param $id
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updatePlainUser(UpdatePlainUserRequest $request, $id) {
        if (($notAdmin = $this->isAdmin($this->oneself)) && ((int) $id !== 0)) {
            return $notAdmin;
        }
        $user = User::find($id);
        if ((int)$id===0){
            $user = $this->oneself;
        }

        if (!$user) {
            return $this->notFound();
        }

        if ($request->has("real_name")) {
            $user->real_name = $request->get("real_name");
        }
        if ($request->has("phone")) {
            $user->phone = $request->get("phone");
        }
        if ($request->has("lottery_num")) {
            $user->lottery_num = $request->get("lottery_num");
        }
        if ($request->has("remarks")) {
            $user->remarks = $request->get("remarks");
        }

        $this->saveModel($user);

        return $this->success(User::find($user->id));
    }

    /**
     * 更新商铺老板雇员信息
     *
     * @param \App\Http\Requests\UpdateShopEmployeeRequest $request
     * @param                                              $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateShopEmployee(UpdateShopEmployeeRequest $request, $id) {
        if ($notAdmin = $this->isAdmin($this->oneself)) {
            return $notAdmin;
        }

        $user = User::find($id);

        if (!$user) {
            return $this->notFound();
        }
        if ($user->identity != 1 && $user->identity != 2) {
            return $this->badRequest(NULL, ResponseMessage::$message[403000]);
        }
        if ($request->has("shop_id") && !!Shop::find($request->get("shop_id"))) {
            $this->notFound();
        }

        if ($request->has("real_name")) {
            $user->real_name = $request->get("real_name");
        }
        if ($request->has("phone")) {
            $user->phone = $request->get("phone");
        }
        if ($request->has("identity")) {
            $user->identity = $request->get("identity");
        }
        if ($request->has("state")) {
            $user->state = $request->get("state");
        }
        if ($request->has("remarks")) {
            $user->remarks = $request->get("remarks");
        }

        $this->saveModel($user);

        if ($request->has("shop_id")) {
            $user->shop()->detach();
            $user->shop()->attach($request->get("shop_id"));
        }

        return User::find($user->id);
    }

    /**
     * 删除指定商铺老板雇员
     *
     * @param  int $id
     *
     * @return void
     */
    public function removeShopEmployee($id) {
        if ($notAdmin = $this->isAdmin($this->oneself)) {
            return $notAdmin;
        }

        User::find($id)->shop()->detach();
        User::destroy($id);

        return;
    }

    /**
     * 老板删除自己的雇员
     *
     * @param $id
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function removeShopEmployeeByBoss($id) {
        if ($notBoss = $this->isBoss($this->oneself)) {
            return $notBoss;
        }

        $shops = $this->oneself->shops();
        if (!$shops->get()->isEmpty()) {
            return $this->badRequest(NULL, ResponseMessage::$message[400028]);
        }

        User::find($id)->shop()->detach();
        User::destroy($id);
    }
}
