<?php
namespace backint\core;
use backint\core\DBObj;
use backint\core\http;
use backint\core\ErrObj;

require_once("./core/ErrObj.php");
require_once("./core/DBObj.php");
require_once("./config/config.php");
require_once("./definitions/HTTP.php");
require_once("./definitions/ConfigTables.php");
require_once("./core/http.php");
require_once("./definitions/SQLFormat.php");

class Config {
    private string $tableName;
    public function __construct($tableName) {
        $this->tableName = TABLE_CONFIG_PREFIX."_".$tableName;
    }

    public function getConfig() {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$this->tableName.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        $jsonResponse = '{ "configurations": [';
        $index = 0;
        $http = new http();
        $badFormed = false;
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                foreach (TABLE_CONFIG_STRUCTURE as $table => $value) {
                    if(!array_key_exists($value["name"], $row))
                        $badFormed = true;
                }
                if($badFormed) {
                    $err = new ErrObj("Bad formed configuration table on DB", INTERNAL_SERVER_ERROR);
                    $err->sendError();
                    die;
                }
                if($index > 0)
                    $jsonResponse .= ',';
                $jsonResponse .= '{ ';
                $internalIndex = 0;
                foreach (TABLE_CONFIG_STRUCTURE as $table => $value) {
                    if($internalIndex > 0)
                        $jsonResponse .= ',';
                    $jsonResponse .= '"'.$value["name"].'": ';
                    if(SQL_FORMAT[$value["type"]])
                        $jsonResponse .= '"'.$row[$value["name"]].'"';
                    else
                        $jsonResponse .= $row[$value["name"]];
                    $internalIndex++;
                }
                $jsonResponse .= '}';
                $index++;
            }
            $jsonResponse .= ']}';
            $http->sendResponse(OK, $jsonResponse);
        }
        else
        {
            $err = new ErrObj("Resource did not found", NO_CONTENT);
            $err->sendError();
        }
    }
}
?>