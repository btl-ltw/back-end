<?php

class getPublisherBooksController extends publisherController {
    public function GET () {
        $this->responseJsonData($this->bookRepository->getBookFromPublisher($_SESSION['username']));
    }
}