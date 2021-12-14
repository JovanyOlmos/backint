<?php
namespace backint\core;

require("./core/QueryBuilder/Extensions/JoinBuilder.php");
require("./core/QueryBuilder/Extensions/LimitBuilder.php");
require("./core/QueryBuilder/Extensions/OrderBuilder.php");
require("./core/QueryBuilder/Extensions/WhereBuilder.php");

use backint\core\QueryBuilder\JoinBuilder;
use backint\core\QueryBuilder\LimitBuilder;
use backint\core\QueryBuilder\OrderBuilder;
use backint\core\QueryBuilder\WhereBuilder;

class QueryBuilder {

    /**
     * Class constructor
     */
    public function __construct() {
        
    }

    /**
     * Get where builder
     * 
     * @return WhereBuilder
     */
    public function where() {
        return WhereBuilder::getInstance();
    }

    /**
     * Get order builder
     * 
     * @return OrderBuilder
     */
    public function orderBy() {
        return OrderBuilder::getInstance();
    }

    /**
     * Get join builder
     * 
     * @return void
     */
    public function join() {
        return JoinBuilder::getInstance();
    }

    /**
     * Get limit builder
     * 
     * @return LimitBuilder
     */
    public function limit(): LimitBuilder {
        return LimitBuilder::getInstance();
    }
}
?>