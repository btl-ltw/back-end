<?php

class loginController extends authController {
    public function PUT () {
        $userData = $this->userdataRepository->findByUsername($this->requestBody['username']);

        if($userData['password'] == $this->requestBody['password']) {
            $token = $this->setCookieToken($this->requestBody['username']);

            $this->responseJsonData($token);
        }

        $this->responseJsonData("Sai mật khẩu", 401);
    }
}