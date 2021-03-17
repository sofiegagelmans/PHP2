<?php
class DBManager
{
    private $logger;
    private $conn;
    private $dbconfig;

    public function __construct(Logger $logger, array $dbconfig)
    {
        $this->logger = $logger;
        $this->dbconfig = $dbconfig;
    }

    function getConnection()
    {
        //create connection if it doesn't exist already
        if ( $this->conn === null )
        {
            try {
                $this->conn = new PDO("mysql:host=" . $this->dbconfig["server"] . ";dbname=" . $this->dbconfig["database"],
                                                            $this->dbconfig["user"], $this->dbconfig["password"], array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ));
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        return $this->conn;
    }

    function GetData($sql)
    {
        //define, log and execute query
        $this->logger->Log(date("Y-m-d H:i:s: ") . $sql );
        $result = $this->getConnection()->query($sql);

        //return result (if there is any)
        if ($result->rowCount() > 0) {
            $rows = $result->fetchAll(PDO::FETCH_BOTH); //geeft array zoals [0] => 1, ['lan_id'] => 1, ...
            return $rows;
        }
        else return [];

    }

    function ExecuteSQL($sql)
    {
        //define, log and execute query
        $this->logger->Log(date("Y-m-d H:i:s: ") . $sql );
        $result = $this->getConnection()->query($sql);

        return $result;
    }

}

