<?php
namespace backint\server;
use backint\core\ErrObj;
use backint\core\AuthJWT;
use backint\core\DBObj;
use backint\core\Http;
use backint\core\QuickQuery;
use Configuration;

class Router {
    
    /**
     * Execute APIModel function
     * 
     * @param array $action
     * 
     * @param array $params
     * 
     * @param array $requestBody
     */
    public static function process($method, $mainRoute, $params, $requestBody) {
        try {
            require_once("./app/controllers/Controller".ucfirst($mainRoute[0]).".php");
            require_once("./app/models/Model".ucfirst($mainRoute[0]).".php");
            $className = "backint\app\controllers\\";
            $className .= "Controller".ucfirst($mainRoute[0]);
            $db = new DBObj();
            $quickQuery = new QuickQuery($db);
            $class = new $className($quickQuery);
            $routeSettings = $class->getRouteSetting($method, $mainRoute[1]);
            if(Configuration::AUTH_JWT_ACTIVE && $routeSettings["jwt"]) {
                $token = null;
                if(array_key_exists("token", getallheaders()))
                    $token = getallheaders()["token"];
                if(AuthJWT::checkToken($token) == null) {
                    $err = new ErrObj("Invalid token", Http::UNAUTHORIZED);
                    $err->sendError();
                    die();
                }
            }
            $functionName = $mainRoute[1];
            if($requestBody != null)
                $class->$functionName($params, $requestBody);
            else 
                $class->$functionName($params);
        } catch (\Throwable $th) {
            $err = new ErrObj("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), Http::INTERNAL_SERVER_ERROR);
            $err->sendError();
        }
    }
}
?>