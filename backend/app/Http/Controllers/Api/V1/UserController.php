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

    /**
     * 获取所有用户列表
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function list() {
        return $this->success(User::all());
    }

    /**
     * 获取普通用户列表
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function listPlainUser() {
        $plainUserList = User::where("identity", 0)
            ->get()
            ->map(function ($item) {
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
     * 获取所有商铺用户列表(老板、员工)
     *
     * @return mixed
     */
    public function listShopEmployee() {
        $plainUserList = User::where("identity", 1)
            ->orWhere("identity", 2)
            ->get()
            ->map(function ($item) {
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
        if ( ! $user) {
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
     * @param                                           $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updatePlainUser(UpdatePlainUserRequest $request, $id) {
        $user = User::find($id);

        if ( ! $user) {
            return $this->notFound();
        }
        if ($user->identity != 0) {
            return $this->badRequest(NULL, ResponseMessage::$message[403000]);
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

        $this->save_model($user);

        return User::find($user->id);
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

        $this->save_model($user);

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
        User::find($id)->shop()->detach();
        User::destroy($id);
    }
}
