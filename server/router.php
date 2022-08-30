<?php
namespace backint\server;

use backint\core\AuthJWT;
use backint\core\db\builder\MySQLQueryBuilder;
use backint\core\db\MySQL;
use backint\core\db\RelationalQuickQuery;
use backint\core\Http;
use backint\core\ObjQL;
use backint\core\Result;
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
            foreach (glob("./app/models/*.php") as $filename)
            {
                require($filename);
            }
            $className = "backint\\app\\controllers\\";
            $className .= "Controller".ucfirst($mainRoute[0]);
            $db = new MySQL();
            $queryBuilder = new MySQLQueryBuilder();
            $quickQuery = new RelationalQuickQuery($db, $queryBuilder);
            $class = new $className($quickQuery, $queryBuilder);
            $routeSettings = $class->getRouteSetting($method, $mainRoute[1]);
            if(Configuration::AUTH_JWT_ACTIVE && $routeSettings["jwt"]) {
                $token = null;
                if(array_key_exists("token", getallheaders()))
                    $token = getallheaders()["token"];
                if(AuthJWT::checkToken($token) == null) {
                    $err = new Result("Invalid token", false);
                    $err->sendResult(Http::UNAUTHORIZED);
                    die();
                }
            }
            $functionName = $mainRoute[1];
            if($requestBody != null) {
                $class->$functionName($params, $requestBody);
                return;
            }
            $class->$functionName($params);
            return;
        } catch (\Throwable $th) {
            $err = new Result("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), false);
            $err->sendResult(Http::INTERNAL_SERVER_ERROR);
        }
    }
}
?>