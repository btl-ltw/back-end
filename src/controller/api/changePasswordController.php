<?php

class changePasswordController extends apiController {
    public function PUT () {
        $password = $this->requestBody['password'];

        $this->userdataRepository->updatePasswordByUsername($_SESSION['username'], $password);

        $this->responseJsonData("Cập nhật mật khẩu thành công");
    }
}