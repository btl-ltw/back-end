<?php

class logOutController extends authController {

    public function GET () {
        setcookie('token', '', time() - 3600, '/'); // Xóa cookie 'token'

        $this->responseJsonData("Đăng xuất thành công");
    }
}