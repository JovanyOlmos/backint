<?php
namespace backint\core;

abstract class ControllerBase {

    /**
     * Routes settings
     */
    public $ROUTES = array(
        "GET" => array(),
        "POST" => array(),
        "PUT" => array(),
        "DELETE" => array(),
        "PATCH" => array()
    );

    /**
     * Set settings for a route
     * 
     * @param string $method
     * 
     * @param string $function
     * 
     * @param boolean $requireAuth
     * 
     * @return void
     */
    public function setRouteSettings($method, $function, $requireAuth) {
        $this->ROUTES[$method][$function] = array(
            "jwt" => $requireAuth
        );
    }

    /**
     * Get settings for a route
     * 
     * @param string $method
     * 
     * @param string $function
     * 
     * @return mixed
     */
    public function getRouteSetting($method, $function) {
        return $this->ROUTES[$method][$function];
    }

    /**
     * Get a record using its id
     * 
     * @param array array $params
     */
    public abstract function get_by_id($params);

    /**
     * Update a record
     * 
     * @param array array $params
     * 
     * @param array array $requestBody
     */
    public abstract function update($params, $requestBody);

    /**
     * Create a record
     * 
     * @param array array $params
     * 
     * @param array array $requestBody
     */
    public abstract function create($params, $requestBody);

    /**
     * Delete a record
     * 
     * @param array array $params
     */
    public abstract function delete_by_id($params);
}
?>