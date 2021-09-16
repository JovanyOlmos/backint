<?php
namespace backint\server\api;
require_once("./core/OController.php");
require_once("./core/ControllerHelper/SQLControllerHelper.php");
require_once("./core/http.php");
require_once("./core/ObjQL.php");
require_once("./interfaces/OITest.php");
require_once("./core/APIModel.php");
require_once("./definitions/HTTP.php");

use backint\core\OController;
use backint\core\ControllerHelper\SQLControllerHelper;
use backint\interfaces\OITest;
use backint\core\http;
use backint\core\ObjQL;
class APIModelTest extends APIModel {

	private $oiTest;
	private $oController;
	private $http;

	public function __construct() {
		$this->oController = new OController();
		$this->oiTest = new OITest("test", "id");
		$this->http = new http();
	}

	public function getById($params) {
		$helper = new SQLControllerHelper();
		$helper->getControllerFilter()->addPKFilter($this->oiTest->getPKFieldName(), $params[0]);
		$this->oiTest = $this->oController->selectSimple($this->oiTest, $helper);
		if($this->oiTest->getPKValue() > 0)
		{
			$json = $this->http->convertObjectToJSON($this->oiTest);
			$this->http->sendResponse(OK, $json);
		}
		else
		{
			$this->http->sendResponse(NO_CONTENT, $this->http->messageToJSON("Resource does not exist"));
		}
	}

	public function create($params, $requestBody) {
		$this->oiTest = $this->http->fillObjectFromJSON($this->oiTest, $requestBody);
		$err = $this->oController->insert($this->oiTest);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Created correctly"));
	}

	public function update($params, $requestBody) {
		$this->oiTest = $this->http->fillObjectFromJSON($this->oiTest, $requestBody);
		$err = $this->oController->update($this->oiTest);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Updated correctly"));
	}

	public function deleteById($params) {
		$this->oiTest->setPKValue($params[0]);
		$err = $this->oController->delete($this->oiTest);
		if($err->hasErrors())
			$err->sendError();
		else
			$this->http->sendResponse(CREATED, $this->http->messageToJSON("Deleted correctly"));
	}
}
?>