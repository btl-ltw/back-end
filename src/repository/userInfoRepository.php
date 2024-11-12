<?php

class userInfoRepository extends userDataDataBaseRepository {
    public function save($id, $displayName) {
        $sql = "
            INSERT INTO user_info (user_id, display_name)
            VALUES ('$id', '$displayName')
        ";

        $this->queryExecutor($sql);
    }

    public function updateDisplayName($id, $displayName) {
        $sql = "
            UPDATE user_info
            SET display_name = '$displayName'
            WHERE user_id = '$id'
        ";

        $this->queryExecutor($sql);
    }

    public function existById($id) {
        $sql = "
            SELECT * FROM user_info
            WHERE user_id = '$id'
        ";

        $result = $this->getDataFromResult($this->queryExecutor($sql));

        return $result;
    }

    public function getUserInfo($username) {
        $sql = "
            SELECT username, role, email, display_name, img_url, vip_level, credits
            FROM user_data
            INNER JOIN user_info
            ON user_data.id = user_info.user_id
            WHERE username = '$username'
        ";

        return $this->getDataFromResult($this->queryExecutor($sql));
    }

    public function updateCredits($username, $amount) {
        $sql = "
            UPDATE user_info as ui
            JOIN user_data
            ON user_data.id = ui.user_id
            SET credits = ui.credits + '$amount'
            WHERE username = '$username'
        ";

        $this->queryExecutor($sql);
    }
}