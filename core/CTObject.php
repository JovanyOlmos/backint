<?php
namespace backint\core;
require_once("./core/DBObject.php");
require_once("./config/config.php");
require_once("./core/http.php");
require_once("./definitions/HTTP.php");

use backint\core\DBObject;
use backint\core\http;
class CTObject {
    private ?string $tableName;
    public function __construct($tableName) {
        $this->tableName = TABLE_CONFIG_PREFIX."_".$tableName;
    }

    public function getConfig() {
        $dbObject = new DBObject();
        $sqlQuery = "SELECT * FROM ".$this->tableName.";";
        $doFetch = $dbObject->getFetchAssoc($sqlQuery);
        $jsonResponse = '{ "fields": [';
        $index = 0;
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
        $http = new http();
        $http->sendResponse(OK, $jsonResponse);
    }
}
?>