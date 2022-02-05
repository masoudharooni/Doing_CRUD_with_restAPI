<?php

namespace App\Services;

use App\Utilities\UserUtility;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenGenerator
{
    private $payload;
    public function generate(string $email): ?string
    {
        $userId = UserUtility::isExistUserByEmail($email);
        if (is_null($userId))
            return null;
        $this->payload = ['id' => $userId];
        return JWT::encode($this->payload, JWT_KEY, JWT_ALG);
    }

    public function decode(string $jwt, string $key = JWT_KEY, string $alg = JWT_ALG): ?object
    {
        try {
            return JWT::decode($jwt, new key($key, $alg));
        } catch (\Exception $e) {
            return null;
        }
    }
}
