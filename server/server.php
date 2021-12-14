<?php
namespace backint\server;

use backint\core\ErrObj;
use backint\server\Handler;
use backint\core\Http;
use backint\core\Json;
use Configuration;

error_reporting(E_ERROR | E_PARSE | E_WARNING | E_NOTICE);

session_start();

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
        if($apiKey == Configuration::API_KEY) {
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
                $err = new ErrObj("Method Not Allowed [".$this->method."]", Http::METHOD_NOT_ALLOWED);
                $err->sendError();
            }
        } else {
            if($this->method == 'OPTIONS')
            {
                Http::sendResponse(OK, Json::messageToJSON("API is working!!"));
            } else {
                $err = new ErrObj("You do not have authorized to use this API.", Http::UNAUTHORIZED);
                $err->sendError();
            }
        }
    }
}
?>