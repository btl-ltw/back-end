<?php

class managerController extends apiController {
    public function __construct() {
        parent::__construct();

        $user = $this->userdataRepository->findByUsername($_SESSION['username']);

        if($user['role'] != "manager")
            throw new Exception("Role không hợp lệ cho endpoint này");
    }
}