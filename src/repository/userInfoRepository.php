<?php

class userInfoRepository extends userDataDataBaseRepository {
    public function save($id, $displayName) {
        $sql = "
            INSERT INTO user_info (user_id, display_name)
            VALUES (?, ?)
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            // Gắn giá trị vào các placeholder
            $stmt->bind_param("is", $id, $displayName);  // "i" cho id (integer), "s" cho display_name (string)

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function updateDisplayName($id, $displayName) {
        $sql = "
            UPDATE user_info
            SET display_name = ?
            WHERE user_id = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            // Gắn giá trị vào các placeholder (display_name và user_id)
            $stmt->bind_param("si", $displayName, $id);  // "s" cho display_name (string), "i" cho id (integer)

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function existById($id) {
        $sql = "
            SELECT * FROM user_info
            WHERE user_id = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $id);

            $stmt->execute();

            $result = $stmt->get_result();

            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function getUserInfo($username) {
        $sql = "
            SELECT username, role, email, display_name, img_url, vip_level, credits
            FROM user_data
            INNER JOIN user_info
            ON user_data.id = user_info.user_id
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();

            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function updateCredits($username, $amount) {
        $sql = "
            UPDATE user_info as ui
            JOIN user_data
            ON user_data.id = ui.user_id
            SET credits = ui.credits + ?
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ds", $amount, $username);

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }
}