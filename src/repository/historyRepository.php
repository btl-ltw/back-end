<?php

class historyRepository extends historyDataBaseRepository {
    public function getHistoryByUsername($username, $offset) {
        $offset = $offset * 10 ?? 0;

        $sql = "
            SELECT username, amount, time
            FROM payment
            JOIN ltw_user.user_data as ud
            ON payment.user_id = ud.id
            WHERE username = ?
            ORDER BY time DESC
            LIMIT 10 OFFSET ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("si", $username, $offset);

            $stmt->execute();

            $result = $stmt->get_result();

            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function getAllHistoryPayment($offset) {
        $offset = $offset * 10 ?? 0;

        $sql = "
            SELECT username, amount, role, time
            FROM payment 
            JOIN ltw_user.user_data as ud 
            ON payment.user_id = ud.id 
            ORDER BY payment.time ASC
            LIMIT 10 OFFSET ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            // Gắn giá trị vào placeholder (offset)
            $stmt->bind_param("i", $offset);  // "i" chỉ định rằng $offset là kiểu integer

            $stmt->execute();

            $result = $stmt->get_result();

            // Lấy tất cả kết quả dưới dạng mảng kết hợp
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }
}