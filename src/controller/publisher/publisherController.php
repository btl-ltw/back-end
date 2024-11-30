<?php

class publisherController extends apiController {
    protected bookRepository $bookRepository;

    public function __construct() {
        parent::__construct();

        $user = $this->userdataRepository->findByUsername($_SESSION['username']);
        $this->bookRepository = new bookRepository();

        if($user['role'] != 'publisher')
            throw new Exception("Role không hợp lệ cho endpoint này");
    }
}