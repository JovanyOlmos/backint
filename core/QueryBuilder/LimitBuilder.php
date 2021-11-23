<?php
namespace backint\core\QueryBuilder;

class LimitBuilder {

    /**
     * Static instance
     * 
     * @var LimitBuilder
     */
    private static $instance;

    /**
     * Start index
     * 
     * @var int
     */
    private $index;

    /**
     * Num of records
     * 
     * @var int
     */
    private $records;

    /**
     * Constructor
     */
    private function __construct() {
       $this->index = 1;
       $this->records = 10;
    }

    /**
     * Init a limit builder object
     * 
     * @return LimitBuilder
     */
    public static function getInstance(): LimitBuilder {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Get limit's string
     * 
     * @return string
     */
    public function getLimit(): string {
        return " LIMIT ".$this->index.", ".$this->records;
    }

    /**
     * Set the index value
     * 
     * @param int $index
     */
    public function setIndex($index): void {
        $this->index = $index;
    }

    /**
     * Get index value
     * 
     * @return int
     */
    public function getIndex(): int {
        return $this->index;
    }

    /**
     * Set num records value
     * 
     * @param int $records
     */
    public function setRecords($records): void {
        $this->records = $records;
    }

    /**
     * Get num records
     * 
     * @return int
     */
    public function getRecords(): int {
        return $this->records;
    }
}
?>