<?php
namespace backint\app\controllers;

use backint\core\db\builder\QueryBuilder;
use backint\core\db\iQuickQuery;
use backint\app\models\ModelProducts;
use backint\core\Http;
use backint\core\Json;
use backint\core\ControllerBase;
use backint\core\db\builder\WhereBuilder;

class ControllerProducts extends ControllerBase {

	private $modelProducts;

	private iQuickQuery $quickQuery;

	private QueryBuilder $queryBuilder;

	public function __construct(iQuickQuery $_quickQuery, QueryBuilder $_queryBuilder) {
		$this->modelProducts = new ModelProducts();
		$this->quickQuery = $_quickQuery;
		$this->queryBuilder = $_queryBuilder;
		$this->setRouteSettings("GET", "get_by_Id", false);
		$this->setRouteSettings("GET", "get_like", false);
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
	public function get_by_Id($params) {
		$this->queryBuilder->select()->where()->addPKFilter($this->modelProducts, $params[0]);
		$this->queryBuilder->select()->where()->addFilter($this->modelProducts, $this->modelProducts->getName(), WhereBuilder::$LIKE, $params[1]);
		$this->modelProducts = $this->quickQuery->selectSimple($this->modelProducts, $this->queryBuilder);
		if(is_null($this->modelProducts)) {
			Http::sendResponse(Http::NO_CONTENT);
		} else {
			$json = Json::convertModelToJSON($this->modelProducts);
			Http::sendResponse(Http::OK, $json);
		}
	}

	/**
	 * Get some records when they are like a param string
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function get_like($params) {
		$this->queryBuilder->select()->where()->addFilter($this->modelProducts, $this->modelProducts->getName(), WhereBuilder::$EQUALS, $params[0]);
		$arrayModelProducts = $this->quickQuery->selectMultiple($this->modelProducts, $this->queryBuilder);
		if(is_null($arrayModelProducts)) {
			Http::sendResponse(Http::NO_CONTENT);
		} else {
			$json = Json::convertArrayModelToJSON($arrayModelProducts);
			Http::sendResponse(Http::OK, $json);
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
		$this->modelProducts = Json::fillObjectFromJSON($this->modelProducts, $requestBody);
		$result = $this->quickQuery->insert($this->modelProducts);
		if(!$result->getResult()) {
			$result->sendResult(Http::BAD_REQUEST);
		} else {
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Created correctly"));
		}
	}

	/**
	 * Update an existing record passing by body all information
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function update($params, $requestBody) {
		$this->modelProducts = Json::fillObjectFromJSON($this->modelProducts, $requestBody);
		$result = $this->quickQuery->update($this->modelProducts);
		if(!$result->getResult()) {
			$result->sendResult(Http::BAD_REQUEST);
		} else {
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Updated correctly"));
		}
	}

	/**
	 * Delete a record passing by param an id
	 * 
	 * @param mixed $params
	 * 
	 * @return void
	 */
	public function delete_by_id($params) {
		$this->modelProducts->getIdField()->value = $params[0];
		$result = $this->quickQuery->delete($this->modelProducts);
		if(!$result->getResult()) {
			$result->sendResult(Http::BAD_REQUEST);
		} else {
			Http::sendResponse(Http::CREATED, Json::messageToJSON("Deleted correctly"));
		}
	}
}
?>