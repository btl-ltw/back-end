<?php

class envLoaderService {
    private static array $env;

    public static function loadEnv () {
        if(file_exists('../.env')) {
            self::$env = parse_ini_file('../.env');
        }
        else
            throw new Exception("Không tìm thấy file .env");
    }

    public static function getEnv (String $name) {
        return self::$env[$name];
    }
}