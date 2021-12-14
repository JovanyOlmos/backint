<?php
namespace backint\app\controllers;

use backint\core\QuickQuery;
use backint\core\QueryBuilder;
use backint\models\ModelTest;
use backint\core\Http;
use backint\core\Json;
use backint\core\ControllerBase;
use backint\core\ObjQL;

class ControllerTest extends ControllerBase {
	private $modelTest;

	private QuickQuery $quickQuery;

	public function __construct(QuickQuery $_quickQuery) {
		$this->modelTest = new ModelTest();
		$this->quickQuery = $_quickQuery;
		$this->setRouteSettings("GET", "get_by_id", false);
		$this->setRouteSettings("POST", "create", false);
		$this->setRouteSettings("PUT", "update", false);
		$this->setRouteSettings("DELETE", "delete_by_id", false);
	}

	/**
	 * Get a record passing by param an id
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function get_by_id($params) {
		$builder = new QueryBuilder();
		$builder->where()->addPKFilter($this->modelTest->getPKFieldName(), $params[0]);
		$this->modelTest = $this->quickQuery->selectSimple($this->modelTest, $builder);
		if(!is_null($this->modelTest) && $this->modelTest->getPKValue() > 0)
		{
			$json = Json::convertObjectToJSON($this->modelTest);
			Http::sendResponse(Http::OK, $json);
		}
		else
		{
			Http::sendResponse(Http::NO_CONTENT);
		}
	}

	/**
	 * Insert a new record into database passing by body the info
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function create($params, $requestBody) {
		$this->modelTest = Json::fillObjectFromJSON($this->modelTest, $requestBody);
		$err = $this->quickQuery->insert($this->modelTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Created correctly"));
	}

	/**
	 * Update an existing record passing by body all information
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function update($params, $requestBody) {
		$this->modelTest = Json::fillObjectFromJSON($this->modelTest, $requestBody);
		$err = $this->quickQuery->update($this->modelTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Updated correctly"));
	}

	/**
	 * Delete a record passing by param an id
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function delete_by_id($params) {
		$this->modelTest->setPKValue($params[0]);
		$err = $this->quickQuery->delete($this->modelTest);
		if($err->hasErrors())
			$err->sendError();
		else
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Deleted correctly"));
	}
}
?>