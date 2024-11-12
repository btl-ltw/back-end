<?php

class getUserInfoController extends apiController {
    public function GET () {
        $data = $this->userInfoRepository->getUserInfo($_SESSION['username']);

        $this->customResponseData($data);
    }
}