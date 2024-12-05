<?php

class apiController extends Controller {
    protected userDataRepository $userdataRepository;
    protected userInfoRepository $userInfoRepository;
    protected historyRepository $historyRepository;
    protected bookRepository $bookRepository;

    public function __construct() {
        parent::__construct();
        $this->userInfoRepository = new userInfoRepository();
        $this->userdataRepository = new userDataRepository();
        $this->historyRepository  = new historyRepository();
        $this->bookRepository = new bookRepository();

        $headers = getallheaders();
        $token = null;

        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
        }

        if($token == null) {
            $token = $_COOKIE['token'];

            if($token == NULL)
                $this->responseJsonData("Api yêu cầu đăng nhập", 401);
        }

        $decoded = jwtService::validateToken($token);

        $_SESSION['username'] = $decoded->sub;
    }
}