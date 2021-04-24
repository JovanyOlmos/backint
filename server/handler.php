<?php
namespace backint\server;
use backint\core\Config;
use backint\server\router;
use backint\core\http;
use backint\core\ErrObj;
use backint\server\specialRouter;
require_once("./definitions/HTTP.php");
require_once("./core/Config.php");
require_once("./server/router.php");
require_once("./server/specialRouter.php");
require_once("./config/config.php");
require_once("./core/ErrObj.php");

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
            $isSpecialRoute = false;
            foreach (SPECIAL_ROUTES as $specialRoute => $value) {
                if($value == $isConfig)
                    $isSpecialRoute = true;
            }
            if($isSpecialRoute)
            {
                $specialRoute = new specialRouter($isConfig, $params, $requestBody);
            }
            else
            {
                try {
                    $router = new router();
                    if(array_key_exists($router->getIndexRoute($method, $route), $router->routes))
                    {
                        $router->routes[$router->getIndexRoute($method, $route)]->executeProcess($params, $requestBody);
                    }
                    else
                    {
                        $err = new ErrObj("Route does not exist", NOT_FOUND);
                        $err->sendError();
                    }
                } catch (\Throwable $th) {
                    $err = new ErrObj("Fatal error on server routes", INTERNAL_SERVER_ERROR);
                    $err->sendError();
                }
            }
        }
        
    }
}
?>