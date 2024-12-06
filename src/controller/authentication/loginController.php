<?php

class loginController extends authController {
    public function PUT () {
        $userData = $this->userdataRepository->findByUsername($this->requestBody['username']);

        if ($userData && !empty($userData['last_time_blocked'])) {
            $currentTime = new DateTime();
            $lastBlockedTime = new DateTime($userData['last_time_blocked']);

            $interval = $currentTime->diff($lastBlockedTime);

            if ($interval->i < 5) {
                $this->responseJsonData('Tài khoản bị khóa trong ' . 5-$interval->i . ' phút', 403);
            }
        }

        if($userData['password'] == $this->requestBody['password']) {
            $token = $this->setCookieToken($this->requestBody['username']);

            $this->userdataRepository->successLogin($this->requestBody['username']);
            $this->responseJsonData($token);
        }

        $this->userdataRepository->minusTimeAllow($this->requestBody['username']);
        $this->responseJsonData("Sai mật khẩu", 401);
    }
}