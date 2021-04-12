<?php
namespace backint\server\api;
use backint\core\OController;
use backint\interfaces\OIFichas;
use backint\core\http;
require_once("./core/OController.php");
require_once("./core/http.php");
require_once("./interfaces/OIFichas.php");
require_once("./definitions/HTTP.php");

class APIModelFichas {
    
    private OIFichas $oiFichas;
    private OController $oController;
    private http $http;

    public function __construct() {
        $this->oController = new OController();
        $this->oiFichas = new OIFichas("fichas", "id");
        $this->http = new http();
    }

    public function getFichaById($params) {
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

    public function getFichasByIdPersona($params) {
        $arrayFichas = $this->oController->getObjInterfaceArrayByForeignId($params[1], $params[0], $this->oiFichas);
        $json = $this->http->convertArrayObjectToJSON($arrayFichas);
        $this->http->sendResponse(OK, $json);
    }

    public function postFicha($params, $requestBody) {
        $this->oiFichas = $this->http->fillObjectFromJSON($this->oiFichas, $requestBody);
        $err = $this->oController->register($this->oiFichas);
        if($err->hasErrors())
            $err->sendError();
        else 
            $this->http->sendResponse(CREATED, $this->http->messageJSON("Ficha created correctly"));
    }

    public function putFicha($params, $requestBody) {
        $this->oiFichas = $this->http->fillObjectFromJSON($this->oiFichas, $requestBody);
        $this->oiFichas->setIdObject($requestBody[$this->oiFichas->getColumnNameFromIdTable()]);
        $err = $this->oController->put($this->oiFichas);
        if($err->hasErrors())
            $err->sendError();
        else 
            $this->http->sendResponse(CREATED, $this->http->messageJSON("Ficha created correctly"));
    }

    public function deleteFicha($params) {
        $this->oiFichas->setIdObject($params[0]);
        $err = $this->oController->delete($this->oiFichas);
        if($err->hasErrors())
            $err->sendError();
        else 
            $this->http->sendResponse(CREATED, $this->http->messageJSON("Ficha deleted correctly"));
    }
}
?>