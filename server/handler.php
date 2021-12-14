<?php
namespace backint\server;
use backint\server\Router;
use backint\core\ErrObj;
use backint\core\Http;
use Configuration;

class Handler{

    /**
     * Check if is a valid request
     */
    public static function processRequest($method, $route, $requestBody = null) {
        $allParams = str_replace(Configuration::ROUTE_INDEX, "", $route);
        $params = explode("/", $allParams);
        $mainRoute = array_slice($params, 0, 2);
        $params = array_slice($params, 2);
        try {
            Router::process($method, $mainRoute, $params, $requestBody);
        } catch (\Throwable $th) {
            $err = new ErrObj("Fatal error on server. ".$th->getMessage()
                ." Linea: ".$th->getLine()
                ." Archivo: ".$th->getFile(), Http::INTERNAL_SERVER_ERROR);
            $err->sendError();
        }
    }
}
?>