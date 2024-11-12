<?php

class authController extends Controller {
    protected userDataRepository $userdataRepository;

    public function __construct() {
        parent::__construct();

        $this->userdataRepository = new userDataRepository();
    }

    protected function setCookieToken($username) {
        $token = jwtService::createToken($username);

        setcookie('token', $token, time() + 3600, "/");

        return $token;
    }

    //authcontroller không có method cụ thể
}