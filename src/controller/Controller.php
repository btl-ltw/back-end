<?php

class Controller {
    protected $requestBody;

    protected function responseJsonData($message, $code = 200) {
        http_response_code($code);

        $responseData = [
                'date' => date('Y-m-d H:i:s'),
                'code' => $code,
                'message' => $message,
                'path' => $_SERVER["REQUEST_URI"]
        ];

        echo json_encode($responseData);

        exit;
    }

    protected function customResponseData($responseData, $code = 200) {
        http_response_code($code);

        echo json_encode($responseData);

        exit;
    }

    public function __construct() {
        $inputData = file_get_contents('php://input');
        $this->requestBody = json_decode($inputData, true);
    }
}