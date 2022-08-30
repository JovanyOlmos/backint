<?php
namespace backint\core\db\builder;

abstract class LimitBuilder {

    /**
     * Start index
     * 
     * @var int
     */
    private $index = null;

    /**
     * Num of records
     * 
     * @var int
     */
    private $records = null;

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Get limit's string
     * 
     * @return string
     */
    public abstract function getLimit();

    /**
     * Set the index value
     * 
     * @param int $index
     */
    public function setIndex($index) {
        $this->index = $index;
    }

    /**
     * Get index value
     * 
     * @return int
     */
    public function getIndex() {
        return $this->index;
    }

    /**
     * Set num records value
     * 
     * @param int $records
     */
    public function setRecords($records) {
        $this->records = $records;
    }

    /**
     * Get num records
     * 
     * @return int
     */
    public function getRecords() {
        return $this->records;
    }
}
?>