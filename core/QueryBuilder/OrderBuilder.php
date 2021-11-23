<?php
namespace backint\core\QueryBuilder;

class OrderBuilder {

    /**
     * Static instance
     * 
     * @var OrderBuilder
     */
    private static $instance;

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
        $this->typeOrder = OrderBuilder::$ORDER_DESC;
    }

    /**
     * Init a order builder
     * 
     * @return OrderBuilder
     */
    public static function getInstance(): OrderBuilder {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Add a new field to sorting filter
     * 
     * @param Field field to include
     * 
     * @return void
     */
    public function addSort($field): void {
        if(strlen($this->sorting) > 0)
            $this->sorting .= ",";
        $this->sorting .= " ".$field->getColumnName()." ";
    }

    /**
     * Get sorting
     * 
     * @return string MySQL order query
     */
    public function getSort(): string {
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
    public function setSortingType($order): void {
        $this->typeOrder = $order;
    }
}
?>