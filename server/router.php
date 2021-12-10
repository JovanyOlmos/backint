<?php
namespace backint\server;
use backint\core\ErrObj;
use backint\core\AuthJWT;
use Configuration;

require_once("./config/config.php");
require_once("./config/routes.php");
require_once("./core/ErrObj.php");
require_once("./core/AuthJWT.php");

class Router {

    /**
     * Constructor
     */
    public function __construct() {
        
    }
    
    /**
     * Execute APIModel function
     * 
     * @param array $action
     * 
     * @param array $params
     * 
     * @param array $requestBody
     */
    public static function process($action, $params, $requestBody) {
        try {
            require_once("./server/api-models/".$action["class"].".php");
            if(Configuration::AUTH_JWT_ACTIVE && $action["jwt"]) {
                $token = null;
                if(array_key_exists("token", getallheaders()))
                    $token = getallheaders()["token"];
                if(AuthJWT::checkToken($token) == null) {
                    $err = new ErrObj("Invalid token", UNAUTHORIZED);
                    $err->sendError();
                    die();
                }
            }
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