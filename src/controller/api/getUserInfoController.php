<?php

class getUserInfoController extends apiController {
    public function GET () {
        $data = $this->userInfoRepository->getUserInfo($_SESSION['username']);

        $data['role'] = $data['role'] ?? "guest";

        $this->customResponseData($data);
    }
}