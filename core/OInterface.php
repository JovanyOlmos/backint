<?php
namespace backint\core;
require_once("./core/IField.php");
require_once("./config/config.php");
use backint\core\IField;

class OInterface {
    /**
     * Table name
     * 
     * @var string
     */
    private $tableName;

    /**
     * Primary Key field name
     * 
     * @var string
     */
    private $pkFieldName;

    /**
     * Primary key value
     * 
     * @var int
     */
    private $pkValue = 0;

    /**
     * Fields from the table. Array assoc with column name as key.
     * 
     * @var array assoc
     */
    public $fields;

    /**
     * Constructor
     * 
     * @param string $tableName
     * 
     * @param string $pkFieldName primary key field name
     */
    public function __construct($tableName, $pkFieldName) {
        $this->fields = array();
        $this->tableName = $tableName;
        $this->pkFieldName = $pkFieldName;
    }

    /**
     * Add a new field to OInterface object
     * 
     * @param string $columnName
     * 
     * @param string $sqlFormat
     * 
     * @return IField
     */
    public function addField($columnName, $sqlFormat) {
        $iField = new IField($columnName, $sqlFormat);
        $this->fields[$columnName] = $iField;
        return $iField;
    }

    /**
     * Get the table name
     * 
     * @return string
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * Get primary key field name
     * 
     * @return string
     */
    public function getPKFieldName() {
        return $this->pkFieldName;
    }

    /**
     * Get Id
     * 
     * @return int
     */
    public function getPKValue() {
        return $this->pkValue;
    }

    /**
     * Set Id
     * 
     * @param int
     */
    public function setPKValue($id) {
        $this->pkValue = $id;
    }
}
?>