<?php

class userDataDataBaseRepository extends databaseRepository {
    public function __construct() {
        $DB_NAME = envLoaderService::getEnv("USER_DB");

        $this->connect($DB_NAME);
    }
}