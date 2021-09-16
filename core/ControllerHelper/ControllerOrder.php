<?php
namespace backint\core\ControllerHelper;

class ControllerOrder {
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

    /**
     * Static sort value
     * 
     * @var string Descending
     */
    public static $ORDER_DESC = " DESC ";

    /**
     * Static sort value
     * 
     * @var string Ascending
     */
    public static $ORDER_ASC = " ASC ";
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->sorting = "";
        $this->typeOrder = ControllerOrder::$ORDER_DESC;
    }

    /**
     * Add a new field to sorting filter
     * 
     * @param IField field to include
     * 
     * @return void
     */
    public function addSort($iField) {
        if(strlen($this->sorting) > 0)
            $this->sorting .= ",";
        $this->sorting .= " ".$iField->getColumnName()." ";
    }

    /**
     * Get sorting
     * 
     * @return string MySQL order query
     */
    public function getSort() {
        $orderBy = "";
        if(strlen($this->sorting) > 0)
            $orderBy = " ORDER BY ".$this->sorting." ".$this->typeOrder;
        return $orderBy;
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
}
?>