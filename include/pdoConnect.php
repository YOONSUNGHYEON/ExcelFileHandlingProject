<?php

class pdoConnect
{

    private $dbHost = "127.0.0.1";
    private $dbName = "dbProduct";
    private $dbUser = "zc753951";
    private $dbPass = "7566";
    private $dbChar = "utf8"; 

    function connectPdo()
    {
        try {
            $dsn = "mysql:host=$this->dbHost;dbname=$this->dbName;charset=$this->dbChar";
            $pdo = new PDO($dsn, $this->dbUser, $this->dbPass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $pdo;
        } catch (PDOException $e) {
            die('연결 실패: ' . $e->getMessage());
        }
    }
    function __destruct()
    {
        
    }
}


