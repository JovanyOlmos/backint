<?php
namespace backint\server;
use backint\core\ErrObj;

require_once("./config/config.php");
require_once("./config/routes.php");
require_once("./core/ErrObj.php");

class router{
    /**
     * Constructor
     * Execute APIModel function
     * 
     * @param array $action
     * 
     * @param array $params
     * 
     * @param array $requestBody
     */
    public function __construct($action, $params, $requestBody) {
        try {
            require_once("./server/api-models/".$action["class"].".php");
            $className = "backint\server\api\\";
            $className .= $action["class"];
            $functionName = $action["function"];
            $execute = new $className();
            if($requestBody != null)
                $execute->$functionName($params, $requestBody);
            else 
                $execute->$functionName($params);
        } catch (\Throwable $th) {
            $err = new ErrObj("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), INTERNAL_SERVER_ERROR);
            $err->sendError();
        }
    }
}
?>