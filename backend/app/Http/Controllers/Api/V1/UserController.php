<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\UpdatePlainUserRequest;
use App\Model\User;
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function save(Request $request) {
        //
    }

    /**
     * 通过 id 查找用户
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function get_user_by_id($id) {
        if ($id == 0) {
            return $this->success(JWTAuth::parseToken()->authenticate());
        }
        $user = User::find($id);
        if ( ! $user) {
            return $this->notFound(NULL, "未找到此用户");
        }

        return $this->success($user);
    }

    /**
     * 更新用户信息
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
            return $this->badRequest(NULL, "用户身份不符合");
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
     * 删除指定id用户
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function remove($id) {
        //
    }
}
