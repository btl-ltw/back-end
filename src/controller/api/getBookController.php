<?php

class getBookController extends apiController {
    public function GET () {
        $category = $_GET['category'] ?? null;
        $book_id = $_GET['book_id'] ?? null;
        $offset = $_GET['offset'] ?? 0;

        if($book_id)
            $data = $this->bookRepository->getBookById($book_id);

        else $data = $this->bookRepository->getBook($category, $offset);

        $this->responseJsonData($data);
    }
}