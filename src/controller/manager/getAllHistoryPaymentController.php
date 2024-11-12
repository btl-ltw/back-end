<?php

class getAllHistoryPaymentController extends managerController {
    public function GET () {
        $offset = $_GET['offset'] ?? null;

        $result = $this->historyRepository->getAllHistoryPayment($offset);

        if($result)
            $this->customResponseData($result);

        throw new Exception("Không tìm thấy dữ liệu");
    }
}