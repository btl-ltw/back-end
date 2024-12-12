<?php

class getAllCategoryController extends apiController {
    public function GET () {
        $this->responseJsonData($this->bookRepository->getAllCategory());
    }
}