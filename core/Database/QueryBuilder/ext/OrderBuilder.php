<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;

abstract class OrderBuilder {

    /**
     * Sorting variable
     * 
     * @var string
     */
    private $sorting;

    /**
     * Has sorting type
     * 
     * @var string Default DESC
     */
    private $typeOrder;

    public static $DESC = "D";

    public static $ASC = "A";
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->sorting = "";
        $this->typeOrder = OrderBuilder::$DESC;
    }

    /**
     * Add a new field to sorting filter
     * 
     * @param Model 
     * 
     * @param ModelField field to include
     * 
     * @return void
     */
    public abstract function addSort($model, $field);

    /**
     * Get sorting
     * 
     * @return string order query
     */
    public abstract function getSort();

    /**
     * Get sorting variable
     * 
     * @return string
     */
    public function getSortingVar() {
        return $this->sorting;
    }

    /**
     * Set sorting variable
     * 
     * @param string
     * 
     * @return string
     */
    public function setSortingVar($sorting) {
        $this->sorting .= $sorting;
    }

    /**
     * Set sorting type. NOTE: use static variables
     * 
     * @param string $order NOTE: use static variables
     * 
     * @return void
     */
    public function setSortingType($order) {
        $this->typeOrder = $order;
    }

    /**
     * Get sorting type
     * 
     * @return string
     */
    public function getSortingType() {
        return $this->typeOrder;
    }
}
?>