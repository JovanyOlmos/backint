<?php
namespace backint\server;
use backint\server\router;
use backint\core\http;
use backint\core\ErrObj;
use backint\update\UpdateEngine;

require_once("./definitions/HTTP.php");
require_once("./server/router.php");
require_once("./config/config.php");
require_once("./core/ErrObj.php");
require_once("./config/routes.php");
require_once("./updates/Update-Engine.php");

class handler{

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Check if is a valid request
     */
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
        try {
            if($mainRoute == "update-models") {
                $updates = new UpdateEngine();
                $updates->runUpdates();
                die();
            }
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
            $err = new ErrObj("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), INTERNAL_SERVER_ERROR);
            $err->sendError();
        }
        
    }
}
?>