<?php
require_once("./config/path-definitions/get-routes.php");
require_once("./config/path-definitions/delete-routes.php");
require_once("./config/path-definitions/post-routes.php");
require_once("./config/path-definitions/put-routes.php");
require_once("./config/path-definitions/patch-routes.php");
define("ROUTES", array(
    "PUT" => PUT_ROUTES,
    "POST" => POST_ROUTES,
    "GET" => GET_ROUTES,
    "DELETE" => DELETE_ROUTES,
    "PATCH" => PATCH_ROUTES,
));

class Routes {
    private $routes = array(
        "PUT" => array(),
        "POST" => array(),
        "GET" => array(),
        "DELETE" => array(),
        "PATCH" => array()
    );

    public function __construct($_router) {
        
    }

    public function PUT($route_info) {
        $this->routes["PUT"]->array_push($route_info);
    }

    public function GET($route_info) {
        $this->routes["GET"]->array_push($route_info);
    }

    public function POST($route_info) {
        $this->routes["POST"]->array_push($route_info);
    }

    public function PATCH($route_info) {
        $this->routes["PATCH"]->array_push($route_info);
    }

    public function DELETE($route_info) {
        $this->routes["DELETE"]->array_push($route_info);
    }

    public function getRoutes() {
        return $this->routes;
    }
}
?>