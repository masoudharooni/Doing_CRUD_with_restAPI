<?php

namespace App;

class Connect
{
    # Object connection to dataBase by PDO
    protected $conn;
    # Data base information
    private $hostName = "localhost";
    private $userName = "root";
    private $password = "";
    private $dbName = "iran";
    private $charSet = "utf8mb4";
    private $dbms = "mysql";

    public function connect()
    {
        $dsn = "{$this->dbms}:host={$this->hostName};dbname={$this->dbName};charset={$this->charSet}";
        try {
            $this->conn = new \PDO($dsn, $this->userName, $this->password);
        } catch (\PDOException $exception) {
            die("Connection Failed : Line -> {$exception->getLine()} , 
            File -> {$exception->getFile()} , and for this reason : 
                {$exception->getMessage()}");
        }
    }
}
