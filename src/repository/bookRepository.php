<?php

class bookRepository extends bookDataBaseRepository {
    public function insertNewBook($data) {
        $sql = "
            INSERT INTO book (publisher_id, name, image_url, category)
            VALUES (
                '{$data['id']}',
                '{$data['name']}',
                '{$data['image_url']}',
                '{$data['category']}'
            )
        ";

        $this->queryExecutor($sql);
    }

    public function insertNewChapter($data) {
        $sql = "
            INSERT INTO chapter (book_id, file_url, chapter_name, chapter_num, price)
            VALUES (
                '{$data['book_id']}',
                '{$data['file_url']}',
                '{$data['chapter_name']}',
                '{$data['chapter_num']}',
                '{$data['price']}'
            )
        ";

        $this->queryExecutor($sql);
    }

    public function getBook($category) {
        $sql = null;

        if($category) {
            $sql = "
                SELECT * FROM `book` 
                WHERE category = '$category'
            ";
        }

        else {
            $sql = "
                SELECT * FROM `book` 
            ";
        }

        return $this->getDataFromResult($this->queryExecutor($sql));
    }

    public function getChapter($book_id, $chapter, $username) {
        $sql = null;

        if($chapter) {
            $sql = "
                SELECT * FROM chapter a
                WHERE a.book_id = '$book_id' AND a.chapter_num = '$chapter'
            ";

            $data = $this->getDataFromResult($this->queryExecutor($sql));

            $sql2 = "
                SELECT credits FROM ltw_user.user_data
                JOIN ltw_user.user_info
                ON ltw_user.user_info.user_id = ltw_user.user_data.id
                WHERE username = 'lisajones12'
            ";

            $data_2 = $this->getDataFromResult($this->queryExecutor($sql2));

//            print_r($data_2[0]['credits']);
//            print_r($data[0]['price']);

            $new_money = $data_2[0]['credits'] - $data[0]['price'];

            if($new_money < 0) {
                throw new Exception("Không đủ tiền mua chapter này");
            }

            $sql3 = "
                UPDATE ltw_user.user_data
                JOIN ltw_user.user_info
                ON ltw_user.user_info.user_id = ltw_user.user_data.id
                SET credits = '$new_money'
                WHERE username = 'lisajones12'
            ";

            $this->queryExecutor($sql3);

            return $this->getDataFromResult($this->queryExecutor($sql));
        }
        else {
            $sql = "
                SELECT id, book_id, chapter_name, chapter_num, price FROM chapter a
                WHERE a.book_id = '$book_id' 
            ";

            return $this->getDataFromResult($this->queryExecutor($sql));
        }
    }
}