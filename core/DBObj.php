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

    public function getFetchAssoc(string $query) {
        if($result = mysqli_query($this->connection, $query))
            return $result;
        else
            return null;
    }

    public function doQuery(string $query) {
        if($result = mysqli_query($this->connection, $query))
            return new ErrObj("", CREATED);
        else
            return new ErrObj("".mysqli_error($this->connection), CONFILCT);
    }
}
?>