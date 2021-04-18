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

class Config {
    private string $tableName;
    public function __construct($tableName) {
        $this->tableName = TABLE_CONFIG_PREFIX."_".$tableName;
    }

    public function getConfig() {
        $dbObject = new DBObj();
        $sqlQuery = "SELECT * FROM ".$this->tableName.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        $jsonResponse = '{ "fields": [';
        $index = 0;
        $http = new http();
        if($doFetch != null)
        {
            while($row = $doFetch->fetch_assoc())
            {
                if($index > 0)
                    $jsonResponse .= ',';
                $jsonResponse.= '{ 
                    "field": "'.$row["name_field"].'",
                    "label_es": "'.$row["label_es"].'", 
                    "label_en": "'.$row["label_en"].'", 
                    "type": "'.$row["type"].'", 
                    "required": '.$row["required"].',
                    "visible": '.$row["visible"].',
                    "enabled": '.$row["enabled"].'
                }';
                $index++;
            }
            $jsonResponse .= ']}';
            $http->sendResponse(OK, $jsonResponse);
        }
        else
        {
            $http->sendResponse(NO_CONTENT, $http->messageJSON("Resource did not found"));
        }
    }
}
?>