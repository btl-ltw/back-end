<?php

class delelteBookController extends publisherController {
    public function DELETE () {
        $book_id = $_GET['book_id'];

        $this->bookRepository->deleteBookById($book_id);

        $this->responseJsonData("Xóa Thành Công");
    }
}