<?php
namespace backint\Server;

use backint\server\Handler;
use backint\core\Http;
use backint\core\Json;
use backint\core\Result;
use Configuration;

error_reporting(E_ERROR | E_PARSE | E_WARNING | E_NOTICE);

session_start();

header("Access-Control-Allow-Origin: ".Configuration::ALLOWED_ORIGINS);
header("Access-Control-Allow-Methods: ".Configuration::ALLOWED_METHODS);
header("Access-Control-Allow-Headers: ".Configuration::ALLOWED_HEADERS);
header("Content-Type: application/json; charset=".Configuration::DEFAULT_CHARSET);

class Server{

    public static function run() {
        $route = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $json = file_get_contents("php://input");
        $apiKey = null;
        if(array_key_exists("api-key", getallheaders()))
            $apiKey = getallheaders()["api-key"];
        $requestBody = json_decode($json, true);
        if($apiKey == Configuration::API_KEY) {
            $isValidMethod = false;
            foreach (explode(",", Configuration::ALLOWED_METHODS) as $value) {
                if($value == $method)
                    $isValidMethod = true;
            }
            if($isValidMethod)
            {
                Handler::processRequest($method, $route, $requestBody);
                return;
            }
            $err = new Result("Method Not Allowed [".$method."]", false);
            $err->sendResult(Http::METHOD_NOT_ALLOWED);
            return;
        }
        if($method == 'OPTIONS')
        {
            Http::sendResponse(Http::OK, Json::messageToJSON("API is working!!"));
            return;
        }
        $err = new Result("You do not have authorized to use this API.", false);
        $err->sendResult(Http::UNAUTHORIZED);
    }
}
?>