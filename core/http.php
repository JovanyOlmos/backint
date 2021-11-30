<?php
namespace backint\core;

class Http {

    /**
     * Send a HTTP response with a JSON as body and its respective code
     * 
     * @param int $code
     * 
     * @param string $json
     * 
     * @return void
     */
    public static function sendResponse($code, $json): void {
        $sapi_type = php_sapi_name();
        if (substr($sapi_type, 0, 3) == 'cgi')
            header("Status: ".$code." ".HTTP_MESSAGE[$code]."");
        else
            header("HTTP/1.1 ".$code." ".HTTP_MESSAGE[$code]."");
        echo $json;
    }
}
?>