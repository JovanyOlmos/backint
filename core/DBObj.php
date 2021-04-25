<?php
namespace backint\core;
require_once("./core/ErrObj.php");
require_once("./config/config.php");
require_once("./definitions/HTTP.php");
use Mysqli;
class DBObj {
    private $connection;

    public function __construct() {
        $this->connection = new mysqli(HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE);
    }

    public function getFetchAssoc($query) {
        if($result = mysqli_query($this->connection, $query)) {
            mysqli_close($this->connection);
            return $result;
        }
        else {
            mysqli_close($this->connection);
            return null;
        }
    }

    public function doQuery($query) {
        if($result = mysqli_query($this->connection, $query)) {
            mysqli_close($this->connection);
            return new ErrObj("", CREATED);
        }
        else {
            mysqli_close($this->connection);
            return new ErrObj("".mysqli_error($this->connection), CONFILCT);
        }
    }
}
?>