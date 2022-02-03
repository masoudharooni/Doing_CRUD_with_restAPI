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
    public function get(int $columnId = null, int $page = null, int $pageSize = null, string $fields = null, string $order = null)
    {
        $limit = '';
        $fields = $fields ?? '*';
        $order = $order ?? "ASC";
        $order = "ORDER BY id {$order}";
        if (!is_null($page) and !is_null($pageSize)) {
            $start_from_which_column = ($page - 1) * $pageSize;
            $limit = " LIMIT {$start_from_which_column},{$pageSize}";
        }
        if (is_null($columnId)) {
            $sql = "SELECT {$fields} FROM {$this->tableName} {$order} {$limit}";
        } else {
            $sql = "SELECT {$fields} FROM {$this->tableName} WHERE id = ? {$order} {$limit}";
        }
        $stmt = $this->conn->prepare($sql);
        if (is_null($columnId)) {
            $stmt->execute([]);
        } else {
            $stmt->execute([$columnId]);
        }
        return $stmt->fetchAll(\PDO::FETCH_OBJ) ?? null;
    }
}
