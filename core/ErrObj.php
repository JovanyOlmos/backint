<?php
namespace backint\core;
use backint\core\http;

require_once("./definitions/HTTP.php");
require_once("./core/http.php");
class ErrObj {
    /**
     * Error message
     * 
     * @var string
     */
    private $message;

    /**
     * Error code
     * 
     * @var int
     */
    private $code;
    
    /**
     * Initialize an ErrObj. Use it for error handler.
     * 
     * @param string $err -> Mensaje de error.
     * 
     * @param int $code -> Http Error Code
     */
    public function __construct($err, $code) {
        $this->message = $err;
        $this->code = $code;
    }

    /**
     * Check if the object has errors
     * 
     * @return boolean
     */
    public function hasErrors(): bool {
        if($this->message != "")
            return true;
        else
            return false;
    }

    /**
     * Get the error message
     * 
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * Get the error code
     * 
     * @return int
     */
    public function getErrCode(): int {
        return $this->code;
    }

    /**
     * Set the error message
     * 
     * @param string
     */
    public function setMessage($message): void {
        $this->message = $message;
    }

    /**
     * Set the error code
     * 
     * @param int
     */
    public function setCode($code): void {
        $this->code = $code;
    }

    /**
     * Send a response with error code and a message
     * 
     * @return void
     */
    public function sendError(): void {
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