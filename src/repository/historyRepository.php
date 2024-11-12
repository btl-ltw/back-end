<?php

class historyRepository extends historyDataBaseRepository {
    public function getHistoryByUsername($username, $offset) {
        $offset = $offset * 10 ?? 0;

        $sql = "
            SELECT username, amount, time
            FROM payment
            JOIN ltw_user.user_data as ud
            ON payment.user_id = ud.id
            WHERE username = '$username'
            ORDER BY time DESC
            LIMIT 10 OFFSET $offset
        ";

        return $this->getDataFromResult($this->queryExecutor($sql));
    }

    public function getAllHistoryPayment($offset) {
        $offset = $offset * 10 ?? 0;

        $sql = "
            SELECT username, amount, role, time
            FROM payment 
            JOIN ltw_user.user_data as ud 
            ON payment.user_id = ud.id 
            ORDER BY `payment`.`time` ASC
            LIMIT 10 OFFSET $offset
        ";

        return $this->getDataFromResult($this->queryExecutor($sql));
    }
}