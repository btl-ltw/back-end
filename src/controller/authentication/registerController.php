<?php

class registerController extends authController {

    public function POST() {
        $this->userdataRepository->save($this->requestBody['username'], $this->requestBody['email'], $this->requestBody['password']);
        $token = $this->setCookieToken($this->requestBody['username']);

        $this->responseJsonData($token);
    }
}