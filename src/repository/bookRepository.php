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
}