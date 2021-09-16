<?php
namespace backint\core;
require_once("./definitions/HTTP.php");
require_once("./definitions/SQLFormat.php");

class Http {

    /**
     * Static value response
     * 
     * @var int 100
     */
    public static $HTTP_CONTINUE = 100;

    /**
     * Static value response
     * 
     * @var int 101
     */
    public static $HTTP_SWITCHING_PROTOCOL = 101;

    /**
     * Static value response
     * 
     * @var int 200
     */
    public static $HTTP_OK = 200;

    /**
     * Static value response
     * 
     * @var int 201
     */
    public static $HTTP_CREATED = 201;

    /**
     * Static value response
     * 
     * @var int 202
     */
    public static $HTTP_ACCEPTED = 202;

    /**
     * Static value response
     * 
     * @var int 204
     */
    public static $HTTP_NO_CONTENT = 204;

    /**
     * Static value response
     * 
     * @var int 205
     */
    public static $HTTP_RESET_CONTENT = 205;

    /**
     * Static value response
     * 
     * @var int 206
     */
    public static $HTTP_PARTIAL_CONTENT = 206;

    /**
     * Static value response
     * 
     * @var int 300
     */
    public static $HTTP_MULTIPLE_CHOISE = 300;

    /**
     * Static value response
     * 
     * @var int 301
     */
    public static $HTTP_MOVED_PERMANENTLY = 301;

    /**
     * Static value response
     * 
     * @var int 400
     */
    public static $HTTP_BAD_REQUEST = 400;


    /**
     * Static value response
     * 
     * @var int 100
     */
    public static $HTTP_UNAUTHORIZED = 401;

    /**
     * Static value response
     * 
     * @var int 403
     */
    public static $HTTP_FORBIDDEN = 403;

    /**
     * Static value response
     * 
     * @var int 404
     */
    public static $HTTP_NOT_FOUND = 404;

    /**
     * Static value response
     * 
     * @var int 405
     */
    public static $HTTP_METHOD_NOT_ALLOWED = 405;

    /**
     * Static value response
     * 
     * @var int 408
     */
    public static $HTTP_REQUEST_TIME_OUT = 408;

    /**
     * Static value response
     * 
     * @var int 409
     */
    public static $HTTP_CONFILCT = 409;

    /**
     * Static value response
     * 
     * @var int 500
     */
    public static $HTTP_INTERNAL_SERVER_ERROR = 500;

    /**
     * Standard message for each code
     * 
     * @var array assoc array with Http Code as key
     */
    public static $HTTP_MESSAGE = array(
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
        500 => "INTERNAL SERVER ERROR");

    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * Convert a string into a JSON with a message property
     * 
     * @param string $message
     * 
     * @return string
     */
    public function messageToJSON($message): string {
        $json = '{"message": "'.$message.'"}';
        return $json;
    }

    /**
     * Send a HTTP response with a JSON as body and its respective code
     * 
     * @param int $code
     * 
     * @param string $json
     * 
     * @return void
     */
    public function sendResponse($code, $json): void {
        $sapi_type = php_sapi_name();
        if (substr($sapi_type, 0, 3) == 'cgi')
            header("Status: ".$code." ".HTTP_MESSAGE[$code]."");
        else
            header("HTTP/1.1 ".$code." ".HTTP_MESSAGE[$code]."");
        echo $json;
    }

    /**
     * Parse a Json into an interface object
     * 
     * @param OInterface $objInterface
     * 
     * @param array $requestBody
     * 
     * @return OInterface
     */
    public function fillObjectFromJSON($objInterface, $requestBody): OInterface {
        foreach ($objInterface->fields as $key => $iField) {
            if(array_key_exists($key, $requestBody)) {
                $iField->value = $requestBody[$key];
            } else {
                $iField->value = "";
            }
        }
        if(array_key_exists($objInterface->getPKFieldName(), $requestBody))
            $objInterface->setPKValue($requestBody[$objInterface->getPKFieldName()]);
        return $objInterface;
    }

    /**
     * Convert an OInterface into a JSON
     * 
     * @param OInterface $objInterface
     * 
     * @return string
     */
    public function convertObjectToJSON($objInterface): string {
        $json = '{';
        $index = 0;
        if($objInterface->getPKValue() > 0)
            $json .= '"'.$objInterface->getPKFieldName().'": '.$objInterface->getPKValue().',';
        foreach ($objInterface->fields as $key => $iField) {
            if($index > 0)
                $json .= ', ';
            $json .= '"'.$key.'":';
            if(SQL_FORMAT[$iField->getFormat()] && $iField->value != "null")
                $json .= '"'.$iField->value.'"';
            else
            {
                if($iField->getFormat() == BOOLEAN)
                {
                    if($iField->value)
                        $json .= 'true';
                    else
                        $json .= 'false';
                }
                else
                    $json .= $iField->value.'';
            }
            $index++;
        }
        $json .= '}';
        return $json;
    }

    /**
     * Convert an object's array into a JSON
     * 
     * @param OInterface $objInterfaces
     * 
     * @return string
     */
    public function convertArrayObjectToJSON($objInterfaces): string {
        $json = '[';
        $indexObjct = 0;
        foreach ($objInterfaces as $key => $objInterface) {
            if($indexObjct > 0)
                $json .= ',';
            $json .= $this->convertObjectToJSON($objInterface);
            $indexObjct++;
        }
        $json .= ']';
        return $json;
    }

    /**
     * Validate if a JSON and an OInterface have the same fields
     * 
     * @param OInterface $objInterface
     * 
     * @param array $requestBody
     * 
     * @return bool
     */
    public function checkIfJSONIsComplete($objInterface, $requestBody): bool {
        $exists = true;
        foreach ($objInterface->fields as $key => $iField) {
            if(!array_key_exists($key, $requestBody)) {
                $exists = false;
            }
        }
        return $exists;
    }
}
?>