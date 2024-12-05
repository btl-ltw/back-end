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

    public function getChapter($book_id, $chapter) {
        $sql = null;

        if($chapter) {
            $sql = "
                SELECT * FROM chapter a
                WHERE a.book_id = '$book_id' AND a.chapter_num = '$chapter'
            ";
        }
        else {
            $sql = "
                SELECT * FROM chapter a
                WHERE a.book_id = '$book_id' 
            ";
        }

        return $this->getDataFromResult($this->queryExecutor($sql));
    }
}