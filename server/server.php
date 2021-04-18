<?php
namespace backint\server;
use backint\core\ErrObj;
use backint\server\handler;
use backint\core\http;
require_once("./core/http.php");
require_once("./core/ErrObj.php");
require_once("./server/handler.php");
require_once("./definitions/HTTP.php");
require_once("./config/config.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=utf-8");
session_start();
class server{
    private string $method;
    private $requestBody;

    public function __construct() {
        $route = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $json = file_get_contents("php://input");
        $apiKey = null;
        foreach (getallheaders() as $nombre => $valor) {
            if($nombre == "api-key")
                $apiKey = $valor;
        }
        $this->requestBody = json_decode($json, true);
        $this->serve($route, $apiKey);
    }

    public function serve($route, $apiKey) {
        if($apiKey == API_KEY) {
            if($this->method == 'POST' || $this->method == 'PUT' || $this->method == 'GET' || $this->method == 'DELETE')
            {
                $handler = new handler();
                $handler->processRequest($this->method, $route, $this->requestBody);
            }
            else
            {
                $err = new ErrObj("Method Not Allowed [".$this->method."]", METHOD_NOT_ALLOWED);
                $err->sendError();
            }
        } else {
            if($this->method == 'OPTIONS')
            {
                $http = new http();
                $http->sendResponse(OK, $http->messageJSON("API is working!!"));
            } else {
                $err = new ErrObj("You do not have authorized to use this API.", UNAUTHORIZED);
                $err->sendError();
            }
        }
    }
}
?>