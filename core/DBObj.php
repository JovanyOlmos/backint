<?php
namespace backint\core;
use backint\core\ErrObj;
require_once("./core/ErrObj.php");

use Mysqli, Exception;

class DBObj {

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
     * @param string $query
     * 
     * @return mysqli_result
     */
    public function getFetchAssoc($query) {
        $this->initConn();
        try {
            if($result = mysqli_query($this->connection, $query)) {
                $this->num_records = $result->num_rows;
                mysqli_close($this->connection);
                return $result;
            }
            else {
                mysqli_close($this->connection);
                return null;
            }
        } catch (Exception $th) {
            $err = new ErrObj("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), INTERNAL_SERVER_ERROR);
            $err->sendError();
            mysqli_close($this->connection);
            die();
        }
    }

    /**
     * Execute a MySql query.
     * 
     * @param string $query
     * 
     * @return ErrObj
     */
    public function doQuery($query): ErrObj {
        $this->initConn();
        if($result = mysqli_query($this->connection, $query)) {
            mysqli_close($this->connection);
            return new ErrObj("", CREATED);
        }
        else {
            $err = mysqli_error($this->connection);
            mysqli_close($this->connection);
            return new ErrObj("".$err, CONFILCT);
        }
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
     * Connect to DB. Initialize a MySQLi Object
     */
    private function initConn() {
        try {
            $this->connection = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
        } catch (Exception  $th) {
            $err = new ErrObj("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), INTERNAL_SERVER_ERROR);
            $err->sendError();
            die();
        }
    }
}
?>