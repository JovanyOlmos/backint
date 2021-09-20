<?php
namespace backint\core\ControllerHelper;

class ControllerUnion {

    /**
     * Data source joins
     * 
     * @var string
     */
    private $unions;

    /**
     * Union type left
     * 
     * @var string
     */
    public static $LEFT = "LEFT JOIN";

    /**
     * Union type right
     * 
     * @var string
     */
    public static $RIGHT = "RIGHT JOIN";

    /**
     * Union type inner
     * 
     * @var string
     */
    public static $INNER = "INNER JOIN";

    /**
     * Union type outer
     * 
     * @var string
     */
    public static $OUTER = "FULL OUTER JOIN";

    /**
     * Constructor
     */
    public function __construct() {
        $this->unions = "";
    }

    /**
     * Add a new data source and establish its relationship in the query
     * 
     * @param OInterface $firstOInterface
     * 
     * @param OInterface $secondOInterface
     * 
     * @param string $firstFieldMatch
     * 
     * @param string $secondFieldMatch
     * 
     * @param string $unionType
     * 
     * @return void
     */
    public function addJoin($firstOInterface, $secondOInterface, $firstFieldMatch, $secondFieldMatch, $unionType) {
        $this->unions .= " ".$unionType." ".$firstOInterface->getTableName()
            ." ON ".$firstOInterface->getTableName()."."
            .$firstFieldMatch." = ".$secondOInterface->getTableName().".".$secondFieldMatch." ";
    }

    /**
     * Get joins
     * 
     * @return string
     */
    public function getJoin() {
        return $this->unions;
    }
}
?>