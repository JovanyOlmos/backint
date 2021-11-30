<?php
namespace backint\core;
require_once("./config/config.php");

class IField {
    /**
     * Database value type
     * 
     * @var string
     */
    private $format;

    /**
     * Column name from database
     * 
     * @var string
     */
    private $columnName;

    /**
     * Column default value
     */
    private $default = null;

    /**
     * Value from field
     * 
     * @var any
     */
    public $value;

    /**
     * Constructor
     * 
     * @param string $DBColumnName
     * 
     * @param string $format MySQL field type
     */
    public function __construct($columnName, $format){
        $this->columnName = $columnName;
        $this->format = $format;
    }

    /**
     * Get type from database
     * 
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * Get column name
     * 
     * @return string
     */
    public function getColumnName() {
        return $this->columnName;
    }

    /**
     * Get default
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * Set field type
     * 
     * @param string $type MySQL field type
     */
    public function setFormat($type) {
        $this->format = $type;
    }

    /**
     * Set column name
     * 
     * @param string $name
     */
    public function setColumnName($name) {
        $this->columnName = $name;
    }

    /**
     * Set default value
     */
    public function setDefault($default) {
        $this->default = $default;
    }
}
?>