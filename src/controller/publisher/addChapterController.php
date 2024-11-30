<?php

class addChapterController extends publisherController {
    public function POST() {
        $book_id = $_POST['book_id'];
        $file_url = $_FILES['file_url'];
        $file_data = file_get_contents($_FILES['file_url']['tmp_name']);
        $chapter_name = $_POST['chapter_name'];
        $chapter_num = $_POST['chapter_num'];
        $price = $_POST['price'] ?? 0;

        $this->bookRepository->insertNewChapter(
            [
                'book_id' => $book_id,
                'file_url' => $file_data,
                'chapter_name' => $chapter_name,
                'chapter_num' => $chapter_num,
                'price' => $price
            ]
        );

        $this->responseJsonData("Cập nhật chapter thành công");
    }
}