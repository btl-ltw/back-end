<?php

class getBookController extends apiController {
    public function GET () {
        $category = $_GET['category'] ?? null;
        $book_id = $_GET['book_id'] ?? null;
        $data = null;

        if($book_id)
            $data = $this->bookRepository->getBookById($book_id);

        else $data = $this->bookRepository->getBook($category);

        $this->responseJsonData($data);
    }
}