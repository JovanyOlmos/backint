<?php
namespace backint\server\api;

interface iController {

    /**
     * Path definitions
     */
    public static function routes();

    /**
     * Get a record using its id
     * 
     * @param array array $params
     */
    public function getById($params);

    /**
     * Update a record
     * 
     * @param array array $params
     * 
     * @param array array $requestBody
     */
    public function update($params, $requestBody);

    /**
     * Create a record
     * 
     * @param array array $params
     * 
     * @param array array $requestBody
     */
    public function create($params, $requestBody);

    /**
     * Delete a record
     * 
     * @param array array $params
     */
    public function deleteById($params);
}
?>