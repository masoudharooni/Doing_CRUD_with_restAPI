<?php

namespace App;

include "interfaces.php";
abstract class Iran extends Connect implements \addAble, \deleteAble, \updateAble, \getAble
{
    # Table name property for CRUD
    protected string $tableName;

    /* Construct method : 
     call connect method for connection to the dataBase
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * delete method 
     *
     * @param integer $columnId
     * @return boolean
     */
    public function delete(int $columnId): bool
    {
        $sql = "DELETE FROM {$this->tableName} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$columnId]) ?: false;
    }

    /**
     * Get method
     * !Note : If the parameter be null, this method will return all column from the table
     * !Note :And if the parameter contains a column id this method will return only the column
     * @param integer|null $columnId
     * @return object
     */
    public function get(int $columnId = null)
    {
        is_null($columnId) ? $sql = "SELECT * FROM {$this->tableName}" : $sql = "SELECT * FROM {$this->tableName} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        is_null($columnId) ? $stmt->execute() : $stmt->execute([$columnId]);
        return $stmt->fetchAll(\PDO::FETCH_OBJ) ?? null;
    }
}
