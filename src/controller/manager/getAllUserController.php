<?php

class getAllUserController extends managerController {
    public function GET () {
        $offset = $_GET['offset'] ?? null;

        $result = $this->userdataRepository->getAllUserInfo($offset);

        if($result)
            $this->customResponseData($result);

        throw new Exception("Không tìm thấy dữ liệu");
    }
}