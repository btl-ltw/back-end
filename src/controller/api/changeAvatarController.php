<?php

class changeAvatarController extends apiController {
    public function PUT() {
        $url = $this->requestBody['url'];
        $username = $_SESSION['username'];

        $this->userdataRepository->updateAvatar($username, $url);
        $this->responseJsonData("Cập nhật thành công");
    }
}