<?php

class assignRoleController extends managerController {
    public function PUT() {
        $username = $_GET['username'];
        $role = $_GET['role'];

        $this->userdataRepository->updateRoleByUsername($username, $role);

        $this->responseJsonData("Cập nhật role cho user" . $username . " thành công");
    }
}