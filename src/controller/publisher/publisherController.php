<?php

class publisherController extends apiController {
    public function __construct() {
        parent::__construct();

        $user = $this->userdataRepository->findByUsername($_SESSION['username']);

        if($user['role'] != 'publisher')
            throw new Exception("Role không hợp lệ cho endpoint này");
    }
}