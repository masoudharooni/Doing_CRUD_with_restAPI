<?php

namespace App\Utilities;

class UserUtility
{
    public static function isExistUserByEmail(string $email): ?array
    {
        foreach (USERS as $user)
            if ($user['email'] == $email)
                return $user;
        return null;
    }
    public static function isExistUserById(int $id): ?array
    {
        foreach (USERS as $user)
            if ($user['id'] == $id)
                return $user;
        return null;
    }

    private static function hasAccessUser(array $user, int $parameterId = null, string $requestedParameter): bool
    {
        if (is_null($parameterId) && !in_array($user['role'], ['admin', 'president']))
            return false;
        if (in_array($user['role'], ['admin', 'president']))
            return true;
        $parameterControler = $requestedParameter . "_access_control";
        if (in_array($parameterId, $user[$parameterControler]))
            return true;
        return false;
    }

    public static function  hasAccessToCities(array $user, int $cityId = null)
    {
        return self::hasAccessUser($user, $cityId, 'city');
    }
    public static function hasAccessToProvinces(array $user, int $provinceId = null)
    {
        return self::hasAccessUser($user, $provinceId, 'province');
    }
}
