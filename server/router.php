<?php
namespace backint\server;
use backint\core\Config;
use backint\core\Route;
require_once("./core/Config.php");
require_once("./core/Route.php");
require_once("./config/config.php");

//API Model files
use backint\server\api\APIModelFichas;
require_once("./server/api-models/APIModelFichas.php");
use backint\server\api\APIModelUsuarios;
require_once("./server/api-models/APIModelUsuarios.php");

class router{
    private $routeConfig;
    public ?array $routes;

    public function __construct() {
        $this->addRoute("GET", "config/?", new APIModelFichas(), "getFichaById");
        $this->addRoute("GET", "fichas/?", new APIModelFichas(), "getFichaById");
        $this->addRoute("GET", "fichas/idpersona/?", new APIModelFichas(), "getFichasByIdPersona");
        $this->addRoute("POST", "fichas", new APIModelFichas(), "postFicha");
        $this->addRoute("PUT", "fichas", new APIModelFichas(), "putFicha");
        $this->addRoute("DELETE", "fichas/?", new APIModelFichas(), "deleteFicha");
        $this->addRoute("POST", "usuarios", new APIModelUsuarios(), "postUser");
        $this->addRoute("GET", "usuarios/?", new APIModelUsuarios(), "getUser");
    }

    private function addRoute(string $type, string $URL, $apiModel, string $functionName) {
        $this->routes[$type."_".$URL] = new Route($type, $URL, $apiModel, $functionName);
    }

    public function getIndexRoute(string $type, string $URL) {
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