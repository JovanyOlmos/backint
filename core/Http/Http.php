<?php
namespace backint\core;

class Http {

    const CONTINUE = 100;

    const SWITCHING_PROTOCOL = 101;
    
    const OK = 200;
    
    const CREATED = 201;
    
    const ACCEPTED = 202;
    
    const NO_CONTENT = 204;
    
    const RESET_CONTENT = 205;
    
    const PARTIAL_CONTENT = 206;
    
    const MULTIPLE_CHOISE = 300;
    
    const MOVED_PERMANENTLY = 301;
    
    const BAD_REQUEST = 400;
    
    const UNAUTHORIZED = 401;
    
    const FORBIDDEN = 403;
    
    const NOT_FOUND = 404;
    
    const METHOD_NOT_ALLOWED = 405;
    
    const REQUEST_TIME_OUT = 408;
    
    const CONFILCT = 409;
    
    const INTERNAL_SERVER_ERROR = 500;
    
    /**
     * Http code meaning
     */
    const HTTP_MESSAGE = array(
        100 => "CONTINUE",
        101 => "SWITCHING PROTOCOL",
        200 => "OK",
        201 => "CREATED",
        202 => "ACCEPTED",
        204 => "NO CONTENT",
        205 => "RESET CONTENT",
        206 => "PARTIAL CONTENT",
        300 => "MULTIPLE CONTENT",
        301 => "MOVED PERMANENTLY",
        400 => "BAD REQUEST",
        401 => "UNAUTHORIZED",
        403 => "FORBIDDEN",
        404 => "NOT FOUND",
        405 => "METHOD NOT ALLOWED",
        408 => "REQUEST TIME OUT",
        409 => "CONFLICT",
        500 => "INTERNAL SERVER ERROR"
    );

    /**
     * Send a HTTP response with a JSON as body and its respective code
     * 
     * @param int $code
     * 
     * @param string $json
     * 
     * @return void
     */
    public static function sendResponse($code, $json = ""): void {
        $sapi_type = php_sapi_name();
        if (substr($sapi_type, 0, 3) == 'cgi')
            header("Status: ".$code." ".Http::HTTP_MESSAGE[$code]."");
        else
            header("HTTP/1.1 ".$code." ".Http::HTTP_MESSAGE[$code]."");
        echo $json;
    }
}
?>