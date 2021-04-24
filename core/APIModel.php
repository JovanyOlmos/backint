<?php
namespace backint\core;
use backint\core\OController;
use backint\core\http;
use backint\core\OInterface;

require_once("./core/OController.php");
require_once("./core/http.php");
require_once("./core/OInterface.php");
require_once("./definitions/HTTP.php");

class APIModel {

    public function simpleGet($oInterface, $params) {
        $this->oiFichas = $this->oController->fillObjInterfaceById($params[0], $this->oiFichas);
        if($this->oiFichas->getIdObject() > 0)
        {
            $json = $this->http->convertObjectToJSON($this->oiFichas);
            $this->http->sendResponse(OK, $json);
        }
        else
        {
            $this->http->sendResponse(NO_CONTENT, $this->http->messageJSON("Resource does not exist"));
        }
    }
}
?>