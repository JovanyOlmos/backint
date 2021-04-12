<?php
namespace backint\core;
use backint\core\http;
require_once("./definitions/HTTP.php");
require_once("./core/http.php");
class ErrObj {
    private ?string $message;
    private ?int $code;
    
    public function __construct(string $err, int $code) {
        $this->message = $err;
        $this->code = $code;
    }

    public function hasErrors() {
        if($this->message != "")
            return true;
        else
            return false;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getErrCode() {
        return $this->code;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function sendError() {
        $sapi_type = php_sapi_name();
        if (substr($sapi_type, 0, 3) == 'cgi')
            header("Status: ".$this->code." ".HTTP_MESSAGE[$this->code]."");
        else
            header("HTTP/1.1 ".$this->code." ".HTTP_MESSAGE[$this->code]."");
        $json = '{"message": "'.$this->message.'"}';
        $http = new http();
        $http->sendResponse($this->code, $json);
    }
}
?>