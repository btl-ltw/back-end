<?php

class getHistoryPaymentController extends apiController {
    public function GET () {
        $offset = $_GET['offset'] ?? null;

        $result = $this->historyRepository->getHistoryByUsername($_SESSION['username'], $offset);

        if($result)
            $this->customResponseData($result);

        throw new Exception("Không tìm thấy dữ liệu");
    }
}