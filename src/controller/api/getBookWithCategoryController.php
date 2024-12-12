<?php

class getBookWithCategoryController extends apiController {
    public function GET () {
        $cate = $_GET['category'];

        $this->responseJsonData($this->bookRepository->getBookWithCategory($cate));
    }
}