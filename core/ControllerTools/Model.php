<?php
namespace backint\core;

use backint\core\ModelField;

class Model {
    
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
     */
    public function __construct() {
        $this->fields = array();
        $this->tableName = "";
        $this->pkFieldName = "id";
    }

    /**
     * Add a new field to Model object
     * 
     * @param string $columnName
     * 
     * @param string $sqlFormat
     * 
     * @return ModelField
     */
    public function addField($columnName, $sqlFormat, $default = null) {
        $modelField = new ModelField($columnName, $sqlFormat);
        if(!is_null($default))
            $modelField->setDefault($default);
        $this->fields[$columnName] = $modelField;
        return $modelField;
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
     * Set table name for model
     */
    public function setTableName($tableName) {
        $this->tableName = $tableName;
    }

    /**
     * Set primary key field name
     */
    public function setPKFieldName($pkFieldName) {
        $this->pkFieldName = $pkFieldName;
    }

    /**
     * Set Id
     * 
     * @param int
     */
    public function setPKValue($id) {
        $this->pkValue = $id;
    }

    /**
     * Return the correct value or format when a field has a null value
     * 
     * @param ModelField
     * 
     * @return string
     */
    public static function nullPropagation($modelField) {
        if($modelField->value == "" || $modelField == "null" || is_null($modelField->value))
        {
            if(is_null($modelField->getDefault()))
                return "null";
            return $modelField->getDefault();
        }
        return $modelField->value;
    }
}
?>