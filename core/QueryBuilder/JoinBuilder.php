<?php
namespace backint\core\QueryBuilder;

class JoinBuilder {

    /**
     * Static instance
     * 
     * @var JoinBuilder
     */
    private static $instance;

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
    private function __construct() {
        $this->unions = "";
    }

    /**
     * Init a limit builder object
     * 
     * @return JoinBuilder
     */
    public static function getInstance(): JoinBuilder {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Add a new data source and establish its relationship in the query
     * 
     * @param OInterface $firstInterface
     * 
     * @param OInterface $secondInterface
     * 
     * @param string $firstFieldMatch
     * 
     * @param string $secondFieldMatch
     * 
     * @param string $unionType
     * 
     * @return void
     */
    public function addJoin($firstInterface, $secondInterface, $firstFieldMatch, $secondFieldMatch, $unionType): void {
        $this->unions .= " ".$unionType." ".$firstInterface->getTableName()
            ." ON ".$firstInterface->getTableName()."."
            .$firstFieldMatch." = ".$secondInterface->getTableName().".".$secondFieldMatch." ";
    }

    /**
     * Get joins
     * 
     * @return string
     */
    public function getJoin(): string {
        return $this->unions;
    }
}
?>