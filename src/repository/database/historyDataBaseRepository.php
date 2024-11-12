<?php

class historyDataBaseRepository extends databaseRepository {
    /**
     * @throws Exception
     */
    public function __construct() {
        $DB_NAME = envLoaderService::getEnv("HISTORY_DB");

        $this->connect($DB_NAME);
    }
}