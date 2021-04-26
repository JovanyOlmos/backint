<?php
namespace backint\server;
use backint\core\Config;
use backint\core\Route;

require_once("./core/Config.php");
require_once("./core/Route.php");
require_once("./config/config.php");

//API Model files
use backint\server\api\APIModelExample;
require_once("./server/api-models/APIModelExample.php");

class router{
    private $routeConfig;
    public $routes;

    public function __construct() {
        $this->addRoute("GET", "example/?", new APIModelExample(), "getExample");
        $this->addRoute("POST", "example", new APIModelExample(), "postExample");
        $this->addRoute("DELETE", "example/?", new APIModelExample(), "deleteExample");
        $this->addRoute("PUT", "example", new APIModelExample(), "putExample");
    }

    private function addRoute($type, $URL, $apiModel, $functionName) {
        $this->routes[$type."_".$URL] = new Route($type, $URL, $apiModel, $functionName);
    }

    public function getIndexRoute($type, $URL) {
        $routeParams = explode("/", $URL);
        $routePrefix = explode("/", ROUTE_INDEX);
        $transformedRoute = "";
        $indexSlash = 0;
        for ($i = count($routePrefix); $i < count($routeParams); $i++) { 
            if($indexSlash > 0)
                $transformedRoute .= "/";
            if(is_numeric($routeParams[$i]))
                $transformedRoute .= "?";
            else
                $transformedRoute .= $routeParams[$i];
            $indexSlash++;
        }
        $transformedRoute = $type."_".$transformedRoute;
        return $transformedRoute;
    }
}
?>