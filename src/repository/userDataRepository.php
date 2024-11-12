<?php

class userDataRepository extends userDataDataBaseRepository {

    public function save($username, $email, $password) {
        $sql = "
            INSERT INTO user_data (username, email, password)
            VALUES ('$username', '$email', '$password')
        ";

        $this->queryExecutor($sql);
    }

    public function findByUsername($username) {
        $sql = "
            SELECT * FROM user_data
            WHERE username = '$username'
        ";

        $array = $this->getDataFromResult($this->queryExecutor($sql));

        if($array == false)
            throw new Exception("không tìm thấy tên đăng nhập");

        return $array[0];
    }

    public function updateEmailByUsername($username, $email) {
        $sql = "
            UPDATE user_data
            SET email = '$email'
            WHERE username = '$username'
        ";

        $this->queryExecutor($sql);
    }

    public function updatePasswordByUsername($username, $password) {
        $sql = "
            UPDATE user_data
            SET password = '$password'
            WHERE username = '$username'
        ";

        $this->queryExecutor($sql);
    }

    public function getIdByUsername($username) {
        $sql = "
            SELECT id FROM user_data
            WHERE username = '$username'
        ";

        return $this->getDataFromResult($this->queryExecutor($sql));
    }

    public function getAllUserInfo($offset) {
        $offset = $offset * 10 ?? 0;

        $sql = "
            SELECT username, role, email, display_name, vip_level, credits
            FROM user_data
            JOIN user_info
            ON user_data.id = user_info.user_id
            LIMIT 10 OFFSET $offset
        ";

        return $this->getDataFromResult($this->queryExecutor($sql));
    }

    public function deleteByUsername($username) {
        $sql = "
            DELETE FROM user_data
            WHERE username = '$username';
        ";

        $this->queryExecutor($sql);
    }

    public function updateRoleByUsername($username, $role) {
        $sql = "
            UPDATE user_data
            SET role = '$role'
            WHERE username = '$username'
        ";

        $this->queryExecutor($sql);
    }
}