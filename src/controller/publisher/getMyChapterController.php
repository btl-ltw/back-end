<?php

class getMyChapterController extends publisherController {
    public function GET () {
        $chapter_id = $_GET['chapter_id'];
        $username = $_SESSION['username'];

        $this->responseJsonData($this->bookRepository->getChapterFromItsAuthor($username, $chapter_id));
    }
}