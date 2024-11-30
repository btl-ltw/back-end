<?php

class publishNewBookController extends publisherController {
    public function POST() {
        $id = $this->userdataRepository->getIdByUsername($_SESSION['username'])['id'];
        $name = $this->requestBody['name'];
        $category = $this->requestBody['category'];
        $image_url = $this->requestBody['image_url'];

        $this->bookRepository->insertNewBook(
            [
                'id' => $id,
                'name' => $name,
                'category' => $category,
                'image_url' => $image_url
            ]
        );

        $this->responseJsonData("Đăng truyện thành công");
    }
}