<?php

use Firebase\JWT\ExpiredException;

class refreshTokenController extends authController {

    public function GET() {
        try {
            $token = $_COOKIE['token'] ?? null;
            if($token === null)
                $this->responseJsonData("Token không hợp lệ", 403);

            jwtService::validateToken($token);

            $this->responseJsonData("Token chưa hết hạn.", 403);
        } catch (ExpiredException $e) {
            $username =  $e->getPayload()->sub;

            $token = $this->setCookieToken($username);
            $this->responseJsonData($token);
        }
    }
}