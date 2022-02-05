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
        $user = UserUtility::isExistUserByEmail($email);
        if (is_null($user))
            return null;
        $this->payload = ['id' => $user['id']];
        return JWT::encode($this->payload, JWT_KEY, JWT_ALG);
    }

    public function decode(string $jwt = null, string $key = JWT_KEY, string $alg = JWT_ALG): ?array
    {
        if (is_null($jwt))
            return null;
        try {
            $payload = JWT::decode($jwt, new key($key, $alg));
            return UserUtility::isExistUserById($payload->id);
        } catch (\Exception $e) {
            return null;
        }
    }
}
