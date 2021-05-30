<?php
namespace backint\server;
use backint\core\Config;
use backint\server\router;
use backint\core\http;
use backint\core\ErrObj;

require_once("./definitions/HTTP.php");
require_once("./core/Config.php");
require_once("./server/router.php");
require_once("./config/config.php");
require_once("./core/ErrObj.php");
require_once("./config/routes.php");

class handler{
    public function __construct() {

    }

    public function processRequest($method, $route, $requestBody = null) {
        $allParams = explode("/", $route);
        $configPrefixUrl = explode("/", ROUTE_INDEX);
        $params = array();
        $index = 0;
        $mainRoute = $allParams[count($configPrefixUrl)];
        for ($i = count($configPrefixUrl) + 1; $i < count($allParams) ; $i++) { 
            $params[$index] = $allParams[$i];
            $index++;
        }
        if($mainRoute == ROUTE_CONFIG) {
            $config = new Config($params[0]);
            $config->getConfig();
        } else {
            try {
                foreach (ROUTES as $key => $value) {
                    if($value["route"] == $mainRoute && $value["type"] == $method)
                    {
                        $router = new router($value, $params, $requestBody);
                        die();
                    }
                }
                $err = new ErrObj("Route does not exist", NOT_FOUND);
                $err->sendError();
            } catch (\Throwable $th) {
                $err = new ErrObj("Fatal error on server routes.", INTERNAL_SERVER_ERROR);
                $err->sendError();
            }
        }
        
    }
}
?>