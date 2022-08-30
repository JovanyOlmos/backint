<?php

namespace backint\core\db;

use backint\core\db\builder\DeleteBuilder;
use backint\core\db\builder\InsertBuilder;
use backint\core\db\builder\SelectBuilder;
use backint\core\db\builder\UpdateBuilder;
use backint\core\Result;
use backint\core\Http;
use Configuration;
use Mysqli, Exception;

class MySQL implements iDBObj {

    /**
     * MySQL connection
     * 
     * @var mysqli
     */
    private $connection;

    /**
     * Number of records in query
     * 
     * @var int
     */
    private $num_records = 0;

    /**
     * Constructor
     */
    public function __construct() {
        
    }

    /**
     * Execute a MySql query and get a mysqli_result object. Use it with a fetch_assoc() function.
     * 
     * @param SelectBuilder $query
     * 
     * @return mysqli_result
     */
    public function getFetchAssoc(SelectBuilder $query) {
        $this->initConn();
        try {
            if($result = mysqli_query($this->connection, $query->getSelect())) {
                if($result->num_rows > 0)
                {
                    $this->num_records = $result->num_rows;
                    mysqli_close($this->connection);
                    return $result;
                }
                mysqli_close($this->connection);
                return null;
            }
            else {
                mysqli_close($this->connection);
                return null;
            }
        } catch (Exception $th) {
            $result = new Result("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), false);
            $result->sendResult(Http::INTERNAL_SERVER_ERROR);
            mysqli_close($this->connection);
            die();
        }
    }

    /**
     * Execute a MySql insert query.
     * 
     * @param InsertBuilder $query
     * 
     * @return Result
     */
    public function doQueryPost(InsertBuilder $query) {
        return $this->executeQuery($query->getInsert());
    }

    /**
     * Execute a MySql delete query.
     * 
     * @param DeleteBuilder $query
     * 
     * @return Result
     */
    public function doQueryDelete(DeleteBuilder $query) {
        return $this->executeQuery($query->getDelete());
    }

    /**
     * Execute a MySql update query.
     * 
     * @param UpdateBuilder $query
     * 
     * @return Result
     */
    public function doQueryPut(UpdateBuilder $query) {
        return $this->executeQuery($query->getUpdate());
    }

    /**
     * Get the number records from a SELECT query
     * 
     * @return int num of records
     */
    public function getNumRecords() {
        return $this->num_records;
    }

    /**
     * Execute query type Update | Delete | Create
     * 
     * @return Result
     */
    private function executeQuery(string $query) {
        try {
            $this->initConn();
            if($result = mysqli_query($this->connection, $query)) {
                mysqli_close($this->connection);
                return new Result("OK", true);
            }
            $err = mysqli_error($this->connection);
            mysqli_close($this->connection);
            return new Result("Error on query: ".$err, false);
        } catch(Exception $_ex) {
            return new Result("Error on query: ".$_ex, false);
        }
    }

    /**
     * Connect to DB. Initialize a MySQLi Object
     */
    private function initConn() {
        try {
            $this->connection = 
                new mysqli(
                    Configuration::DATABASE_HOST, 
                    Configuration::DATABASE_USER, 
                    Configuration::DATABASE_PASSWORD, 
                    Configuration::DATABASE_NAME
                );
        } catch (Exception  $th) {
            $err = new Result("Fatal error on server. ".$th->getMessage()
                ." Line: ".$th->getLine()
                ." File: ".$th->getFile(), false);
            $err->sendResult(Http::INTERNAL_SERVER_ERROR);
            die();
        }
    }
    
}
?>