<?php
namespace backint\server\api;
use backint\core\OController;
use backint\interfaces\OIUsuarios;
use backint\core\http;
require_once("./core/OController.php");
require_once("./core/http.php");
require_once("./interfaces/OIUsuarios.php");
require_once("./definitions/HTTP.php");
class APIModelUsuarios {
    private OIUsuarios $oiUsuarios;
    private OController $oController;
    private http $http;

    public function __construct() {
        $this->oController = new OController();
        $this->oiUsuarios = new OIUsuarios("usuarios", "id");
        $this->http = new http();
    }

    public function postUser($params, $requestBody) {
        $this->oiUsuarios = $this->http->fillObjectFromJSON($this->oiUsuarios, $requestBody);
        $err = $this->oController->register($this->oiUsuarios);
        if($err->hasErrors())
            $err->sendError();
        else 
            $this->http->sendResponse(CREATED, $this->http->messageJSON("Ficha created correctly"));
    }

    public function getUser($params) {
        $this->oiUsuarios = $this->oController->fillObjInterfaceById($params[0], $this->oiUsuarios);
        $json = $this->http->convertObjectToJSON($this->oiUsuarios);
        $this->http->sendResponse(OK, $json);
    }
}
?>