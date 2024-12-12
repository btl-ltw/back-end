<?php

class databaseRepository {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    protected $conn;

    protected function connect($DB_NAME) {
        $this->servername = envLoaderService::getEnv("IP");
        $this->username = envLoaderService::getEnv("USER");
        $this->password = envLoaderService::getEnv("PASSWD");
        $this->dbname = $DB_NAME;

        try {
            $this->conn = new mysqli();
            $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);
            $this->conn->real_connect($this->servername, $this->username, $this->password, $this->dbname);

            if (!$this->conn->set_charset("utf8mb4")) {
                throw new Exception("Không thể thiết lập mã hóa UTF-8: " . $this->conn->error);
            }

        } catch (Exception $e) {
            throw new Exception("Không thể kết nối db, " . $e->getMessage());
        }

        if ($this->conn->connect_error) {
            throw new Exception("Connection failed: " . $this->conn->connect_error);
        }
    }

    protected function queryExecutor($sql) {
        try {
            $result = $this->conn->query($sql);

            if (!$result) {
                throw new Exception("Tạo bảng thất bại!");

                $this->conn->close();
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function getDataFromResult($result) {
        $results = [];

        while ($array = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $results[] = $array;
        }

        if(!$results)
            return false;

        return $results;
    }

    public function __construct() {

    }
}