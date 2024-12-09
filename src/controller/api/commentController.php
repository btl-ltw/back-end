<?php

class commentController extends apiController {
    public function POST() {
        $username = $_SESSION['username'];
        $book_id = $_GET['book_id'];
        $content = $_GET['content'];

        $this->bookRepository->addComment($username, $book_id, $content);

        $this->responseJsonData("Bình luận thành công");
    }

    public function GET () {
        $offset = $_GET['offset'];
        $book_id = $_GET['book_id'];

        $this->responseJsonData($this->bookRepository->getComment($book_id, $offset));
    }

    public function DELETE () {
        $id = $_GET['id'];

        $this->bookRepository->deleteComment($id);

        $this->responseJsonData("Xóa bình luận thành công");
    }
}