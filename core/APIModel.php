<?php
namespace backint\server\api;

abstract class APIModel {
    /**
     * Get a record using its id
     * 
     * @param array array $params
     */
    abstract public function getById($params);

    /**
     * Update a record
     * 
     * @param array array $params
     * 
     * @param array array $requestBody
     */
    abstract public function update($params, $requestBody);

    /**
     * Create a record
     * 
     * @param array array $params
     * 
     * @param array array $requestBody
     */
    abstract public function create($params, $requestBody);

    /**
     * Delete a record
     * 
     * @param array array $params
     */
    abstract public function deleteById($params);
}
?>