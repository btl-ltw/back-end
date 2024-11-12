<?php

class depositController extends apiController {
    public function PUT() {
        $this->userInfoRepository->updateCredits($_SESSION['username'], $_GET['amount']);

        $this->responseJsonData("Nạp tiền thành công");
    }
}