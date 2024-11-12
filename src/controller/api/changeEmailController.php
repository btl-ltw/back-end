<?php

class changeEmailController extends apiController {
    public function PUT() {
        $email = $_GET['email'];

        $this->userdataRepository->updateEmailByUsername($_SESSION['username'], $email);

        $this->responseJsonData("Đổi email thành công!");
    }
}