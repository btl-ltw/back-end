<?php

class changeDisplayNameController extends apiController {
    function POST() {
        $displayName = $_GET['display_name'];
        $id = $this->userdataRepository->getIdByUsername($_SESSION['username'])['id'];

        if($this->userInfoRepository->existById($id)) {
            $this->userInfoRepository->updateDisplayName($id, $displayName);
        } else {
            $this->userInfoRepository->save($id, $displayName);
        }

        $this->responseJsonData("Cập nhật tên thành công");
    }
}