<?php
namespace backint\server;
use backint\core\Config;
use backint\server\router;
use backint\core\http;
require_once("./definitions/HTTP.php");
require_once("./core/http.php");
require_once("./core/Config.php");
require_once("./server/router.php");
require_once("./config/config.php");

class handler{
    public function __construct() {

    }

    public function processRequest($method, $route, $requestBody = null) {
        $allParams = explode("/", $route);
        $configPrefixUrl = explode("/", ROUTE_INDEX);
        $params = array();
        $index = 0;
        $isConfig = $allParams[count($configPrefixUrl)];
        for ($i = count($configPrefixUrl) + 1; $i < count($allParams) ; $i++) { 
            $params[$index] = $allParams[$i];
            $index++;
        }
        if($isConfig == ROUTE_CONFIG) {
            $config = new Config($params[0]);
            $config->getConfig();

        } else {
            $router = new router();
            if(array_key_exists($router->getIndexRoute($method, $route), $router->routes))
            {
                $router->routes[$router->getIndexRoute($method, $route)]->executeProcess($params, $requestBody);
            }
            else
            {
                $http = new http();
                $http->sendResponse(NOT_FOUND, $http->messageJSON("Route does not exist"));
            }
        }
        
    }
}
?>