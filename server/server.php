<?php
namespace backint\server;

use backint\core\ErrObj;
use backint\server\Handler;
use backint\core\http;
use backint\core\Auth;
use backint\core\Json;
use Configuration;

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
session_start();

require_once("./core/http.php");
require_once("./core/ErrObj.php");
require_once("./server/handler.php");
require_once("./config/config.php");
require_once("./core/Auth.php");

header("Access-Control-Allow-Origin: ".Configuration::ALLOWED_ORIGINS);
header("Access-Control-Allow-Methods: ".Configuration::ALLOWED_METHODS);
header("Access-Control-Allow-Headers: ".Configuration::ALLOWED_HEADERS);
header("Content-Type: application/json; charset=".Configuration::DEFAULT_CHARSET);

class server{
    private $method;
    private $requestBody;

    public function __construct() {
        $route = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $json = file_get_contents("php://input");
        $apiKey = null;
        if(array_key_exists("api-key", getallheaders()))
            $apiKey = getallheaders()["api-key"];
        $this->requestBody = json_decode($json, true);
        $this->serve($route, $apiKey);
    }

    private function serve($route, $apiKey) {
        if(!array_key_exists("PHP_AUTH_USER", $_SERVER) || !array_key_exists("PHP_AUTH_PW", $_SERVER))
        {
            $_SERVER['PHP_AUTH_USER'] = "";
            $_SERVER['PHP_AUTH_PW'] = "";
        }
        if($apiKey == Configuration::API_KEY && Auth::checkCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], $this->method)) {
            $isValidMethod = false;
            foreach (explode(",", Configuration::ALLOWED_METHODS) as $value) {
                if($value == $this->method)
                    $isValidMethod = true;
            }
            if($isValidMethod)
            {
                Handler::processRequest($this->method, $route, $this->requestBody);
            }
            else
            {
                $err = new ErrObj("Method Not Allowed [".$this->method."]", METHOD_NOT_ALLOWED);
                $err->sendError();
            }
        } else {
            if($this->method == 'OPTIONS')
            {
                Http::sendResponse(OK, Json::messageToJSON("API is working!!"));
            } else {
                $err = new ErrObj("You do not have authorized to use this API.", UNAUTHORIZED);
                $err->sendError();
            }
        }
    }
}
?>