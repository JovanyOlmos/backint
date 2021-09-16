<?php
namespace backint\core\ControllerHelper;
require_once("./core/ControllerHelper/ControllerFilter.php");
require_once("./core/ControllerHelper/ControllerOrder.php");
use backint\core\ControllerHelper\ControllerOrder;
use backint\core\ControllerHelper\ControllerFilter;

class SQLControllerHelper {
    /**
     * Filter object
     * 
     * @var ControllerFilter
     */
    private $controllerFilter;

    /**
     * Sort object
     * 
     * @var ControllerOrder
     */
    private $controllerOrder;

    /**
     * Constructor
     */
    public function __construct() {
        $this->controllerFilter = new ControllerFilter();
        $this->controllerOrder = new ControllerOrder();
    }

    /**
     * Get controller filter object
     * 
     * @return ControllerFilter
     */
    public function getControllerFilter() {
        return $this->controllerFilter;
    }

    /**
     * Set controller filter object
     * 
     * @param ControllerFilter
     * 
     * @return void
     */
    public function setControllerFilter($controllerFilter) {
        $this->controllerFilter = $controllerFilter;
    }

    /**
     * Get controller order object
     * 
     * @return ControllerOrder
     */
    public function getControllerOrder() {
        return $this->controllerOrder;
    }

    /**
     * Set controller order object
     * 
     * @param ControllerOrder
     * 
     * @return void
     */
    public function setControllerOrder($controllerOrder) {
        $this->controllerOrder = $controllerOrder;
    }
}
?>