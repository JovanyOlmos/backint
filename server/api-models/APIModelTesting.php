<?php
namespace backint\server\api;
require_once("./core/OController.php");
require_once("./core/ControllerHelper/SQLControllerHelper.php");
require_once("./core/http.php");
require_once("./core/ObjQL.php");
require_once("./interfaces/OITesting.php");
require_once("./core/APIModel.php");
require_once("./definitions/HTTP.php");

use backint\core\ControllerHelper\ControllerFilter;
use backint\core\OController;
use backint\core\ControllerHelper\SQLControllerHelper;
use backint\interfaces\OITesting;
use backint\core\http;
use backint\core\ObjQL;
class APIModelTesting extends APIModel {

	private $oiTesting;
	private $oController;
	private $http;

	public function __construct() {
		$this->oController = new OController();
		$this->oiTesting = new OITesting("testing", "id");
		$this->http = new http();
	}

	public function getById($params) {
		$helper = new SQLControllerHelper();
		$helper->getControllerFilter()->addFilter($this->oiTesting->getActivo(), ControllerFilter::$EQUALS, "1");
		$helper->getControllerFilter()->addPKFilter($this->oiTesting->getPKFieldName(), $params[0]);
		$this->oiTesting = $this->oController->selectSimple($this->oiTesting, $helper);
		if($this->oiTesting->getPKValue() > 0)
		{
			$json = $this->http->convertObjectToJSON($this->oiTesting);
			$this->http->sendResponse(OK, $json);
		}
		else
		{
			$this->http->sendResponse(NO_CONTENT, $this->http->messageToJSON("Resource does not exist"));
		}
	}

	public function create($params, $requestBody) {
		$this->oiTesting = $this->http->fillObjectFromJSON($this->oiTesting, $requestBody);
		$err = $this->oController->insert($this->oiTesting);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Created correctly"));
	}

	public function update($params, $requestBody) {
		$this->oiTesting = $this->http->fillObjectFromJSON($this->oiTesting, $requestBody);
		$err = $this->oController->update($this->oiTesting);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Updated correctly"));
	}

	public function deleteById($params) {
		$this->oiTesting->setPKValue($params[0]);
		$err = $this->oController->delete($this->oiTesting);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Deleted correctly"));
	}
}
?>