<?php

class getBookController extends apiController {
    public function GET () {
        $category = $_GET['category'] ?? null;

        $data = $this->bookRepository->getBook($category);

        $this->responseJsonData($data);
    }
}