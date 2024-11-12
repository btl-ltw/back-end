<?php

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Dotenv\Dotenv;
use Firebase\JWT\Key;

class jwtService {
    private static string $issuer = 'MK';
    private static int $expirationTime = 10 * 60;

    public static function createToken($username): string {
        $issuedAt = time();
        $expirationTime = $issuedAt + self::$expirationTime;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $username,
            'iss' => self::$issuer
        ];

        return JWT::encode($payload, envLoaderService::getEnv("JWT_SECRET"), "HS512");
    }

    public static function validateToken($token) {
        $key = envLoaderService::getEnv("JWT_SECRET");

        $decoded = JWT::decode($token, new Key($key, 'HS512'));

        return $decoded;
    }

    public static function getSecretKey(): string {
        return envLoaderService::getEnv('IP');
    }
}
