<?php

class followController extends apiController {
    public function GET () {
        $username = $_SESSION['username'];
        $book_id = $_GET['book_id'] ?? null;

        if($book_id) {
            $this->bookRepository->checkFollow($username, $book_id);
            $this->responseJsonData($this->bookRepository->checkFollow($username, $book_id)['is_exists'] == 0 ? false : true);
        }

        $this->responseJsonData($this->bookRepository->getAllMyFollowedBooks($username));
    }

    public function POST() {
        $username = $_SESSION['username'];
        $book_id = $_GET['book_id'];

        $this->bookRepository->followABook($username, $book_id);
        $this->responseJsonData("Theo dõi sách thành công");
    }

    public function DELETE () {
        $book_id = $_GET['id'];
        $username = $_SESSION['username'];

        $this->bookRepository->cancelFollowABook($username, $book_id);
        $this->responseJsonData("Hủy theo dỏi sách thành công");
    }
}