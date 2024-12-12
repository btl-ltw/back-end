<?php

class deleteUserController extends managerController {
    public function DELETE () {
        $this->userdataRepository->deleteByUsername($_GET['username']);

        $this->responseJsonData("Xóa user thành công");
    }
}