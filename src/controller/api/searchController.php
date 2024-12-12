<?php

class searchController extends apiController {
    public function GET () {
        $key_word = $_GET['key'];

        $this->responseJsonData($this->bookRepository->searchBook($key_word));
    }
}