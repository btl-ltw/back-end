<?php

class userDataRepository extends userDataDataBaseRepository {

    public function save($username, $email, $password) {
        $sql = "
            INSERT INTO user_data (username, email, password)
            VALUES (?, ?, ?)
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sss", $username, $email, $password);

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function findByUsername($username) {
        $sql = "
            SELECT * FROM user_data
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $array = $result->fetch_all(MYSQLI_ASSOC);
            } else {
                throw new Exception("Không tìm thấy tên đăng nhập");
            }

            $stmt->close();

            return $array[0];
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function updateEmailByUsername($username, $email) {
        $sql = "
            UPDATE user_data
            SET email = ?
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ss", $email, $username);

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function updatePasswordByUsername($username, $password) {
        $sql = "
            UPDATE user_data
            SET password = ?
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ss", $password, $username);

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function getIdByUsername($username) {
        $sql = "
        SELECT id FROM user_data
        WHERE username = ?
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $username);

            $stmt->execute();

            // Lấy kết quả
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Lấy dữ liệu
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $data[0];
            } else {
                $stmt->close();
                throw new Exception("Không tìm thấy người dùng");
            }
        } else {
            throw new Exception("Lỗi: " . $this->conn->error);
        }
    }

    public function getAllUserInfo($offset) {
        $offset = $offset * 10 ?? 0;

        $sql = "
            SELECT username, role, email, display_name, vip_level, credits
            FROM user_data
            JOIN user_info
            ON user_data.id = user_info.user_id
            WHERE role IS NULL OR role NOT IN ('manager')
            LIMIT 10 OFFSET ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $offset);

            $stmt->execute();

            $result = $stmt->get_result();

            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function deleteByUsername($username) {
        $sql = "
            DELETE FROM user_data
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $username);

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function updateRoleByUsername($username, $role) {
        $sql = "
            UPDATE user_data
            SET role = ?
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            // Gắn giá trị vào placeholder (role và username)
            $stmt->bind_param("ss", $role, $username);  // "ss" có nghĩa là cả 2 tham số đều là string

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function minusTimeAllow($username) {
        $sql = "
            UPDATE user_data
            SET time_allow = user_data.time_allow - 1
            WHERE username = '$username'
        ";

        $this->queryExecutor($sql);
    }

    public function successLogin($username) {
        $sql = "
            UPDATE user_data
            SET time_allow = 5
            WHERE username = '$username'
        ";

        $this->queryExecutor($sql);
    }
}