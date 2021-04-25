<?php
namespace backint\server;
use backint\core\Config;
require_once("./core/Config.php");
require_once("./config/config.php");

//API Model files
//use backint\server\api\APIModelExample;
//include_once("./server/api-models/APIModelExample.php");

class specialRouter{
    private $actions = array(
        "your-route" => array(
            0 => 'APIModelExample',
            1 => "getExample"
        )
    );

    public function __construct($route, $params, $requestBody) {
        $action = $this->actions[$route];
        $className = "backint\server\api\\";
        $className .= $action[0];
        $functionName = $action[1];
        $execute = new $className();
        if($requestBody != null)
            $execute->$functionName($params, $requestBody);
        else 
            $execute->$functionName($params);
    }
}
?>