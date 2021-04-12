<?php
namespace backint\core;
require_once("./core/ErrObject.php");
require_once("./config/config.php");
require_once("./definitions/HTTP.php");
use Mysqli;
class DBObject {
    private $connection;

    public function __construct() {
        $this->connection = new mysqli(HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE);
    }

    public function getFetchAssoc(string $query) {
        $result = mysqli_query($this->connection, $query);
        return $result;
    }

    public function doQuery(string $query) {
        if($result = mysqli_query($this->connection, $query))
            return new ErrObject("", CREATED);
        else
            return new ErrObject("".mysqli_error($this->connection), CONFILCT);
    }
}
?>