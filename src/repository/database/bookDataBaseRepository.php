<?php

class bookDataBaseRepository extends databaseRepository {
    public function __construct() {
        $DB_NAME = envLoaderService::getEnv("BOOK_DB");

        $this->connect($DB_NAME);
    }
}