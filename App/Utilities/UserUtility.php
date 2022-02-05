<?php

namespace App\Utilities;

class UserUtility
{
    public static function isExistUserByEmail(string $email): ?int
    {
        foreach (USERS as $user)
            if ($user['email'] == $email)
                return $user['id'];
        return null;
    }
    public static function isExistUserById(int $id): bool
    {
        foreach (USERS as $user)
            if ($user['id'] == $id)
                return true;
        return false;
    }
}
