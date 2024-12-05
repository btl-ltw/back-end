<?php

class getChaperFromBookController extends apiController {
    public function GET () {
        $chapter = $_GET['chapter'] ?? null;
        $book_id = $_GET['book_id'];

        $data = $this->bookRepository->getChapter($book_id, $chapter);

        $this->responseJsonData($data);
    }
}