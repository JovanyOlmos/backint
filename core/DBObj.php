<?php
namespace backint\core;
use backint\core\ErrObj;
require_once("./core/ErrObj.php");
require_once("./definitions/HTTP.php");
use Mysqli, Exception, PDOException;

class DBObj {
    private $connection;

    /**
     * Connect to DB. Initialize a MySQLi Object
     */
    public function __construct() {
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

    /**
     * Execute a MySql query and get a mysqli_result object. Use it with a fetch_assoc() function.
     * 
     * @param string $query
     * 
     * @return mysqli_result
     */
    public function getFetchAssoc($query) {
        try {
            if($result = mysqli_query($this->connection, $query)) {
                mysqli_close($this->connection);
                return $result;
            }
            else {
                mysqli_close($this->connection);
                return null;
            }
        } catch (PDOException $th) {
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
}
?>