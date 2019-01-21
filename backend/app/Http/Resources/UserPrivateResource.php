<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPrivateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "username" => $this->username,
            "real_name" => $this->real_name,
            "headimgurl" => $this->headimgurl,
            "phone" => $this->phone,
            "openid" => $this->openid,
            "state" => $this->state,
            "sign_in_num" => $this->sign_in_num,
            "lottery_num" => $this->lottery_num,
            "identity" => $this->identity,
            "created_at" => $this->created_at->format("c"),
            "updated_at" => $this->updated_at->format("c")
        ];
    }
}
