<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UpdatePlainUserRequest;
use App\Http\Requests\UpdateShopEmployeeRequest;
use App\Model\Shop;
use App\Model\User;
use App\Utils\ResponseMessage;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends ApiController {
    /**
     * @return mixed
     */
    public function list() {
        return $this->success(User::all());
    }

    /**
     * 获取普通用户列表
     *
     * @return mixed
     */
    public function listPlainUser() {
        $plainUserList = User::where("identity", 0)->get()->map(function ($item) {
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
        $plainUserList = User::where("identity", 1)->orWhere("identity", 2)->get()->map(function ($item) {
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
        $oneself = JWTAuth::parseToken()->authenticate();
        $shop    = Shop::find($id);
        if ( ! $shop) {
            return $this->badRequest(NULL, ResponseMessage::$message[400028]);
        }

        $shopId = $oneself->shop()->get()->isNotEmpty() ? $oneself->shop()->first()->id : NULL;
        if ($shopId !== $shop->id) {
            return $this->forbidden(NULL, ResponseMessage::$message[403000]);
        }

        return $this->success(
            $shop->users()->get()->map(function ($item) {
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
            $user = JWTAuth::parseToken()->authenticate();

            return $user ? $this->success($user) : $this->unauthorized();
        }
        $user = User::find($id);
        if ( ! $user) {
            return $this->notFound(NULL, ResponseMessage::$message[400007]);
        }

        return $this->success($user);
    }

    /**
     * 更新普通用户信息
     *
     * @param \App\Http\Requests\UpdatePlainUserRequest $request
     * @param                                           $id
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updatePlainUser(UpdatePlainUserRequest $request, $id) {
        $oneself = JWTAuth::parseToken()->authenticate();

        if (($notAdmin = $this->isAdmin($oneself)) && ((int) $id !== 0)) {
            return $notAdmin;
        }
        $user = User::find($id);
        if ((int) $id === 0) {
            $user = $oneself;
        }

        if ( ! $user) {
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
        $user = User::find($id);

        if ( ! $user) {
            return $this->notFound();
        }
        if ($user->identity != 1 && $user->identity != 2) {
            return $this->badRequest(NULL, ResponseMessage::$message[403000]);
        }
        if ($request->has("shop_id") && ! ! Shop::find($request->get("shop_id"))) {
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
     * 删除指定商铺老板雇员 (将商铺老板雇员转至普通用户,并未实际删除)
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function removeShopEmployee($id) {
        $user = User::find($id);
        if ( ! $user) {
            return $this->notFound(NULL, ResponseMessage::$message[400007]);
        }
        $user->shop()->detach();
        $user->identity = 0;
        $this->saveModel($user);
    }

    /**
     * 老板删除自己的雇员
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function removeShopEmployeeByBoss($id) {
        $shops = JWTAuth::parseToken()->authenticate()->shops();
        if ( ! $shops->get()->isEmpty()) {
            return $this->badRequest(NULL, ResponseMessage::$message[400028]);
        }

        User::find($id)->shop()->detach();
        User::destroy($id);
    }
}
