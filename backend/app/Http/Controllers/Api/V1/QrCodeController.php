<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Model\Card;
use App\Utils\ResponseMessage;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Tymon\JWTAuth\Facades\JWTAuth;

class QrCodeController extends ApiController {
    private $oneself;

    /**
     * 生成二维码附带logo
     *
     * @param $text
     *
     * @return mixed
     */
    private function getQrCode($text) {
        $qrCode = QrCode::encoding('UTF-8')
            ->format("png")
            ->size(300)
            ->margin(0);

        if (Storage::exists('qrlogo.jpg')) {
            $qrCode = $qrCode->merge(Storage::path('qrlogo.jpg'), .2, TRUE);
        }

        return $qrCode->generate($text);
    }

    /**
     * 获取管理员登录的二维码base64编码
     *
     * @return mixed
     */
    public function adminLogin() {
        $expiredTime = strtotime("+ 5 minutes");
        $identifier  = str_random(32);
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/login/admin?expired_time=$expiredTime&identifier=$identifier");

        return $this->success([
            "qrcode"     => base64_encode($this->getQrCode($url)),
            "identifier" => $identifier,
        ]);
    }

    /**
     * 获取添加商铺老板的二维码base64编码
     */
    public function addShopBoss() {
        $this->oneself = JWTAuth::parseToken()->authenticate();

        if ($notAdmin = $this->isAdmin($this->oneself)) {
            return $notAdmin;
        }

        $expiredTime = strtotime("+ 5 minutes");
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/add/boss/openid?expired_time=$expiredTime&admin_id=" . $this->oneself->id);

        return $this->success(base64_encode($this->getQrCode($url)));
    }

    /**
     * 获取添加指定商铺员工的二维码base64编码
     *
     * @param $id
     *
     * @return mixed
     */
    public function addShopEmployee($id) {
        $this->oneself = JWTAuth::parseToken()->authenticate();

        if ($notBoss = $this->isBoss($this->oneself)) {
            return $notBoss;
        }

        $expiredTime = strtotime("+ 5 minutes");
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/add/employee?expired_time=$expiredTime&shopid=$id");

        return $url;

        return $this->success(
            base64_encode(
                QrCode::encoding('UTF-8')
                    ->format("png")
                    ->size(300)
                    ->generate($url)
            )
        );
    }

    /**
     * 获取卡券核销的二维码base64编码
     *
     * @param $id
     *
     * @return bool|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function writeOff($id) {
        $this->oneself = JWTAuth::parseToken()->authenticate();

        if ($notPlainUser = $this->isPlainUser($this->oneself)) {
            return $notPlainUser;
        }

        $card = Card::find($id);
        if ( ! $card) {
            return $this->notFound();
        }
        $cardUser = $card->user();
        if ($cardUser->get()->isEmpty()) {
            return $this->badRequest(NULL, ResponseMessage::$message[500004]);
        }
        if ($cardUser->first()->id !== $this->oneself->id) {
            return ResponseMessage::$message[403000];
        }

        $expiredTime = strtotime("+ 5 minutes");
        $url         = env("DOMAIN") . "/wechat/authorize?url=" . urlencode(env("DOMAIN") . "/wechat/grant/writeoff?expired_time=$expiredTime&card_id=" . $card->id);

        return $this->success(base64_encode($this->getQrCode($url)));
    }
}
