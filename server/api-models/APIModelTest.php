<?php
namespace backint\server\api;
require_once("./core/OController.php");
require_once("./core/QueryBuilder/QueryBuilder.php");
require_once("./core/http.php");
require_once("./core/ObjQL.php");
require_once("./interfaces/OITest.php");
require_once("./core/iController.php");
require_once("./definitions/HTTP.php");

use backint\core\OController;
use backint\core\QueryBuilder;
use backint\interfaces\OITest;
use backint\core\http;
use backint\core\ObjQL;

class APIModelTest implements iController {

	private $oiTest;

	public function __construct() {
		$this->oiTest = new OITest("test", "id");
	}

	public function getById($params) {
		$helper = new QueryBuilder();
		$helper->where()->addPKFilter($this->oiTest->getPKFieldName(), $params[0]);
		$this->oiTest = OController::selectSimple($this->oiTest, $helper);
		if($this->oiTest->getPKValue() > 0)
		{
			$json = Http::convertObjectToJSON($this->oiTest);
			Http::sendResponse(OK, $json);
		}
		else
		{
			Http::sendResponse(NO_CONTENT, Http::messageToJSON("Resource does not exist"));
		}
	}

	public function create($params, $requestBody) {
		$this->oiTest = Http::fillObjectFromJSON($this->oiTest, $requestBody);
		$err = OController::insert($this->oiTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(CREATED, Http::messageToJSON("Created correctly"));
	}

	public function update($params, $requestBody) {
		$this->oiTest = Http::fillObjectFromJSON($this->oiTest, $requestBody);
		$err = OController::update($this->oiTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(CREATED, Http::messageToJSON("Updated correctly"));
	}

	public function deleteById($params) {
		$this->oiTest->setPKValue($params[0]);
		$err = OController::delete($this->oiTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(CREATED, Http::messageToJSON("Deleted correctly"));
	}
}
?>