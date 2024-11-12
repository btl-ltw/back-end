<?php

class apiController extends Controller {
    protected userDataRepository $userdataRepository;
    protected userInfoRepository $userInfoRepository;
    protected historyRepository $historyRepository;

    public function __construct() {
        parent::__construct();
        $this->userInfoRepository = new userInfoRepository();
        $this->userdataRepository = new userDataRepository();
        $this->historyRepository  = new historyRepository();

        $headers = getallheaders();
        $token = null;

        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
        }

        if($token == null)
            $this->responseJsonData("Api yêu cầu đăng nhập", 401);

        $decoded = jwtService::validateToken($_COOKIE['token']);

        $_SESSION['username'] = $decoded->sub;
    }
}