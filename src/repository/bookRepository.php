<?php

class bookRepository extends bookDataBaseRepository {
    public function insertNewBook($data) {
        $sql = "
            INSERT INTO book (publisher_id, name, image_url, category)
            VALUES (?, ?, ?, ?)
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            // Gắn giá trị vào các placeholder
            $stmt->bind_param("isss", $data['id'], $data['name'], $data['image_url'], $data['category']);  // "i" cho id (integer), "s" cho name, image_url và category (string)

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function insertNewChapter($data) {
        $sql = "
            INSERT INTO chapter (book_id, file_url, chapter_name, chapter_num, price)
            VALUES (?, ?, ?, ?, ?)
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("isssi", $data['book_id'], $data['file_url'], $data['chapter_name'], $data['chapter_num'], $data['price']);

            if (!$stmt->execute()) {
                throw new Exception("Error: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function getBookById($id) {
        $sql = "
            SELECT * FROM `book` 
            WHERE id = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            // Gắn giá trị vào placeholder
            $stmt->bind_param("i", $id);  // "i" cho id (integer)

            // Thực thi câu lệnh và lấy kết quả
            $stmt->execute();
            $result = $stmt->get_result();

            // Lấy dữ liệu từ kết quả và trả về
            return $this->getDataFromResult($result);
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function getBook($category, $offset) {
        $offset = $offset * 20 ?? 0;
        $sql = null;

        if ($category) {
            $sql = "
                SELECT * FROM `book` 
                WHERE category = ?
                ORDER BY last_update DESC
                LIMIT 20 OFFSET $offset
            ";

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param("s", $category);

                $stmt->execute();
                $result = $stmt->get_result();

                return $this->getDataFromResult($result);
            } else {
                throw new Exception("Error: " . $this->conn->error);
            }
        } else {
            $sql = "
                SELECT * FROM `book`
                ORDER BY last_update DESC
                LIMIT 20 OFFSET $offset
            ";

            $result = $this->queryExecutor($sql);
            return $this->getDataFromResult($result);
        }
    }

    public function getChapter($book_id, $chapter, $username) {
        $sql = null;

        if ($chapter) {
            $sql = "
                SELECT * FROM chapter a
                WHERE a.book_id = ? AND a.chapter_num = ?
            ";

            if ($stmt = $this->conn->prepare($sql)) {
                // Gắn giá trị vào các placeholder
                $stmt->bind_param("ii", $book_id, $chapter);  // "ii" cho book_id và chapter_num (integer)

                $stmt->execute();
                $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                $sql4 = "
                    UPDATE book
                    SET view = view + 1
                    WHERE id = '$book_id'
                ";

                $this->queryExecutor($sql4);

                if($data[0]['price'] == 0)
                    return $data;

                // Câu lệnh thứ hai để lấy credits của người dùng
                $sql2 = "
                    SELECT credits FROM ltw_user.user_data
                    JOIN ltw_user.user_info
                    ON ltw_user.user_info.user_id = ltw_user.user_data.id
                    WHERE username = ?
                ";

                if ($stmt2 = $this->conn->prepare($sql2)) {
                    // Gắn giá trị vào placeholder
                    $stmt2->bind_param("s", $username);  // "s" cho username (string)

                    $stmt2->execute();
                    $data_2 = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
                }

                $new_money = $data_2[0]['credits'] - $data[0]['price'];

                if ($new_money < 0) {
                    throw new Exception("Không đủ tiền mua chapter này");
                }

                // Cập nhật lại số tiền
                $sql3 = "
                    UPDATE ltw_user.user_data
                    JOIN ltw_user.user_info
                    ON ltw_user.user_info.user_id = ltw_user.user_data.id
                    SET credits = ?
                    WHERE username = ?
                ";

                if ($stmt3 = $this->conn->prepare($sql3)) {
                    // Gắn giá trị vào placeholder
                    $stmt3->bind_param("is", $new_money, $username);  // "i" cho credits (integer), "s" cho username (string)

                    $stmt3->execute();
                    $stmt3->close();
                } else {
                    throw new Exception("Error: " . $this->conn->error);
                }

                return $data;  // Trả về dữ liệu chapter đã chọn
            } else {
                throw new Exception("Error: " . $this->conn->error);
            }
        } else {
            $sql = "
                SELECT id, book_id, chapter_name, chapter_num, price FROM chapter a
                WHERE a.book_id = ?
            ";

            if ($stmt = $this->conn->prepare($sql)) {
                // Gắn giá trị vào placeholder
                $stmt->bind_param("i", $book_id);  // "i" cho book_id (integer)

                $stmt->execute();
                $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

                return $data;  // Trả về danh sách các chapter
            } else {
                throw new Exception("Error: " . $this->conn->error);
            }
        }
    }

    public function getBookFromPublisher($username) {
        $sql = "
            SELECT ltw_user.user_data.username, ltw_book.book.id, name, image_url, view, follow, category, last_update
            FROM ltw_user.user_data
            JOIN ltw_book.book
            ON ltw_book.book.publisher_id = ltw_user.user_data.id
            WHERE ltw_user.user_data.username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $username);

            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            return $data;
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function getChapterFromItsAuthor($username, $chapterid) {
        $sql = "
            SELECT ltw_book.chapter.id, file_url, chapter_name, chapter_num, price
            FROM ltw_user.user_data 
            JOIN ltw_book.book
            ON ltw_book.book.publisher_id = user_data.id
            JOIN ltw_book.chapter
            ON ltw_book.chapter.book_id = ltw_book.book.id
            WHERE username = ? AND ltw_book.chapter.id = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            // Gắn giá trị vào các placeholder
            $stmt->bind_param("si", $username, $chapterid);  // "s" cho username (string), "i" cho chapterid (integer)

            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            return $data;
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }

    }

    public function deleteBookById($id) {
        $sql = "
            DELETE FROM book
            WHERE id = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $id);

            $stmt->execute();

            if (!$stmt->affected_rows > 0) {
                Throw new Exception("Query không ảnh hưởng đến bất kì hàng nào của table");
            }
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function deleteChapter($id) {
        $sql = "
            DELETE FROM chapter
            WHERE id = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $id);

            $stmt->execute();

            if (!$stmt->affected_rows > 0) {
                Throw new Exception("Query không ảnh hưởng đến bất kì hàng nào của table");
            }
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }

    }

    public function addComment($username, $book_id, $content) {
        $sql = "
        INSERT INTO comment (username, book_id, content)
        VALUES (?, ?, ?)
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sis", $username, $book_id, $content);

            if (!$stmt->execute()) {
                throw new Exception("Error executing query: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function getComment($book_id, $offset) {
        $offset = isset($offset) ? $offset * 5 : 0;

        $sql = "
        SELECT * FROM comment
        WHERE book_id = ?
        ORDER BY cmt_date DESC
        LIMIT 5 OFFSET ?
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("si", $book_id, $offset);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $data;
            } else {
                throw new Exception("Error executing query: " . $stmt->error);
            }
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function deleteComment($id) {
        $sql = "
        DELETE FROM comment
        WHERE id = ?
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $id); // "i" cho số nguyên (integer)

            if (!$stmt->execute()) {
                throw new Exception("Error executing query: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function followABook($username, $book_id) {
        $sql = "
        INSERT INTO follow (username, book_id)
        VALUES (?, ?)
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("si", $username, $book_id);

            if (!$stmt->execute()) {
                throw new Exception("Error executing query: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function getAllMyFollowedBooks($username) {
        $sql = "
            SELECT * FROM follow
            JOIN book
            ON book.id = follow.book_id
            WHERE username = ?
        ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $data;
            } else {
                throw new Exception("Error executing query: " . $stmt->error);
            }
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function checkFollow($username, $book_id) {
        $sql = "
        SELECT EXISTS (
            SELECT 1 FROM follow
            WHERE username = ? AND book_id = ?
        ) AS is_exists
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("si", $username, $book_id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_assoc();
                $stmt->close();
                return $data;
            } else {
                throw new Exception("Error executing query: " . $stmt->error);
            }
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function cancelFollowABook($username, $book_id) {
        $sql = "
        DELETE FROM follow
        WHERE username = ? AND book_id = ?
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("si", $username, $book_id); // "s" cho chuỗi, "i" cho số nguyên

            if (!$stmt->execute()) {
                throw new Exception("Error executing query: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function getAllCategory() {
        $sql = "
        SELECT category, COUNT(category) AS nums_of_books
        FROM book
        GROUP BY category
        ORDER BY category ASC
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $data;
            } else {
                throw new Exception("Error executing query: " . $stmt->error);
            }
        } else {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
    }

    public function getBookWithCategory($cate) {
        $sql = "
        SELECT * FROM `book` 
        WHERE `category` = ?
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $cate);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $data;
            } else {
                throw new Exception("Error: " . $stmt->error);
            }
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }

    public function searchBook($key) {
        $sql = "
        SELECT * FROM `book` 
        WHERE `name` LIKE CONCAT('%', ?, '%')
        UNION ALL
        SELECT * FROM `book`
        WHERE `category` LIKE ?
    ";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ss", $key, $key);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $data = $result->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                return $data;
            } else {
                throw new Exception("Error: " . $stmt->error);
            }
        } else {
            throw new Exception("Error: " . $this->conn->error);
        }
    }
}