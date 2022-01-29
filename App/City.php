<?php

namespace App;

class City extends Iran
{
    protected string $tableName = "city";
    /**
     * Add a city in the database 
     * This method will return last insert city id
     * @param array $parameters
     * @return integer 
     */
    public function add(array $parameters): int
    {
        $sql = "INSERT INTO {$this->tableName} (province_id , name) VALUES (:province_id , :name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['province_id' => $parameters['province_id'], 'name' => $parameters['name']]);
        return $this->conn->lastInsertId();
    }
    public function update(array $parameters): bool
    {
        $sql = "UPDATE {$this->tableName} SET province_id = :province_id , name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $parameters['id'], ':province_id' => $parameters['province_id'], ':name' => $parameters['name']
        ]) ?: false;
    }
}
