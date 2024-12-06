<?php

class deleteChapterController extends publisherController {
    public function DELETE () {
        $chapter_id = $_GET['chapter_id'];

        $this->bookRepository->deleteChapter($chapter_id);

        $this->responseJsonData("Xóa chapter thành công");
    }
}