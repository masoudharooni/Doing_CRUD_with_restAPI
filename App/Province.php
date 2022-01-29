<?php

namespace App;

class Province extends Iran
{
    protected string $tableName = "province";
    /**
     * Add a province in the database 
     * This method will return last insert province id
     * @param array $parameters
     * @return integer 
     */
    public function add(array $parameters): int
    {
        $sql = "INSERT INTO {$this->tableName} (name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$parameters['name']]);
        return $this->conn->lastInsertId();
    }
    public function update(array $parameters): bool
    {
        $sql = "UPDATE {$this->tableName} SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $parameters['id'], ':name' => $parameters['name']
        ]) ?: false;
    }
}
