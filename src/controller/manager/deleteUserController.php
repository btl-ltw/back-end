<?php

class deleteUserController extends managerController {
    public function PUT () {
        $this->userdataRepository->deleteByUsername($_GET['username']);

        $this->responseJsonData("Xóa user thành công");
    }
}