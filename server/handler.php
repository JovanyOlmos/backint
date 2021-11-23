<?php
namespace backint\server;
use backint\server\Router;
use backint\core\ErrObj;

require_once("./definitions/HTTP.php");
require_once("./server/router.php");
require_once("./config/config.php");
require_once("./core/ErrObj.php");
require_once("./config/routes.php");

class Handler{

    /**
     * Check if is a valid request
     */
    public static function processRequest($method, $route, $requestBody = null) {
        $allParams = explode("/", $route);
        $configPrefixUrl = explode("/", ROUTE_INDEX);
        $params = array();
        $index = 0;
        $mainRoute = $allParams[count($configPrefixUrl)];
        for ($i = count($configPrefixUrl) + 1; $i < count($allParams) ; $i++) { 
            $params[$index] = $allParams[$i];
            $index++;
        }
        try {
            if(array_key_exists($mainRoute, ROUTES[$method])) {
                Router::process(ROUTES[$method][$mainRoute], $params, $requestBody);
                die();
            }
            $err = new ErrObj("Route does not exist", NOT_FOUND);
            $err->sendError();
        } catch (\Throwable $th) {
            $err = new ErrObj("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), INTERNAL_SERVER_ERROR);
            $err->sendError();
        }
    }
}
?>