<?php
namespace backint\core;

use backint\core\Http;

class Result {

    /**
     * Result message
     * 
     * @var string
     */
    private $message;

    /**
     * True if there was no errors
     * 
     * @var bool
     */
    private $result;
    
    /**
     * Initialize a Result.
     * 
     * @param string $err
     * 
     * @param bool $result
     * 
     * @return void
     */
    public function __construct($message, $result) {
        $this->message = $message;
        $this->result = $result;
    }

    /**
     * Return true when there were no errors and false when there were errors
     * 
     * @return boolean
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Get the result message
     * 
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Set the result message
     * 
     * @param string
     * 
     * @return void
     */
    public function setMessage(string $message) {
        $this->message = $message;
    }

    /**
     * Set the result status. FALSE to a negative result
     * 
     * @param bool
     * 
     * @return void
     */
    public function setResult(bool $result) {
        $this->result = $result;
    }

    /**
     * Send a response with error code and a message
     * 
     * @param int
     * 
     * @return void
     */
    public function sendResult($httpCode) {
        $json = new Json();
        $json->addProperty($this->result ? "succesful" : "error", JsProp::$TYPE_STRING, $this->message);
        $jsonString = Json::deserializeJson($json);
        Http::sendResponse($httpCode, $jsonString);
    }
}
?>