<?php
namespace backint\server;
use backint\core\Config;

require_once("./core/Config.php");
require_once("./config/config.php");
require_once("./config/routes.php");
require_once("./config/model-declarations.php");

class router{
    public function __construct($action, $params, $requestBody) {
        $className = "backint\server\api\\";
        $className .= $action["class"];
        $functionName = $action["function"];
        $execute = new $className();
        if($requestBody != null)
            $execute->$functionName($params, $requestBody);
        else 
            $execute->$functionName($params);
    }
}
?>