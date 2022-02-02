<?php

namespace App;

class Validator extends Connect
{
    public function __construct()
    {
        $this->connect();
    }

    private function isExist(string $tableName, int $columnId): bool
    {
        $sql = "SELECT COUNT(id) AS idCounter FROM {$tableName} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$columnId]);
        $result = $stmt->fetch(\PDO::FETCH_OBJ)->idCounter;
        return $result > 0 ? true : false;
    }

    public function isExistCity(int $columnId): bool
    {
        return $this->isExist('city', $columnId);
    }
    public function isExistProvince(int $columnId): bool
    {
        return $this->isExist('province', $columnId);
    }


    public function isValidCity(array $parameters): bool
    {
        if (
            sizeof($parameters) != 2 || !(is_numeric($parameters['province_id']))
            || !(is_string($parameters['name'])) || strlen($parameters['name']) < 2
        )
            return false;
        return true;
    }

    public function areValidFields(string $fields): bool
    {
        $exploadeFields = explode(",", $fields);
        $ourFieldsInDB = ['id', 'province_id', 'name' , '*'];
        foreach ($exploadeFields as $value)
            if (!in_array($value, $ourFieldsInDB))
                return false;
        return true;
    }
}
