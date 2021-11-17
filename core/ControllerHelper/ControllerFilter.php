<?php
namespace backint\core\ControllerHelper;
require_once("./definitions/SQLFormat.php");
class ControllerFilter {

    /**
     * Filter
     * 
     * @var string
     */
    private $filter;

    /**
     * Dynamic value
     * 
     * @var string
     */
    private $dynamicValue;

    /**
     * Dynamic IField
     * 
     * @var IField
     */
    private $dynamicIField;

    /**
     * Dynamic field name to match
     * 
     * @var string
     */
    private $dynamicFieldName;

    /**
     * Static operators
     * 
     * @var string =
     */
    public static $EQUALS = "=";

    /**
     * Static operators
     * 
     * @var string <
     */
    public static $MINUS = "<";

    /**
     * Static operators
     * 
     * @var string >
     */
    public static $MAYOR = ">";

    /**
     * Static operators
     * 
     * @var string <=
     */
    public static $MINUS_EQUALS = "<=";

    /**
     * Static operators
     * 
     * @var string >=
     */
    public static $MAYOR_EQUALS = ">=";

    /**
     * Static operators
     * 
     * @var string LIKE
     */
    public static $LIKE = "LIKE";
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->filter = " WHERE 1 ";
    }

    /**
     * Add a filter to OController
     * 
     * @param IField $iField
     * 
     * @param string $operator
     * 
     * @param string $value
     * 
     * @return void
     */
    public function addFilter($iField, $operator, $value) {
        $this->filter .= " AND ".$iField->getColumnName()." ".$operator." ";
        if(SQL_FORMAT[$iField->getFormat()]) {
            if($iField->getFormat() == 2)
                $this->filter .= " DATE('".$value."')";
            else {
                if($operator == "LIKE")
                    $this->filter .= " '%".$value."%' ";
                else
                    $this->filter .= " '".$value."' ";
            }
        } else {
            $this->filter .= $value;
        }
    }

    /**
     * Set a furute filter, wich one wait a dynamic value
     * 
     * @param IField $iField
     * 
     * @param string $fieldName
     * 
     * @return void
     */
    public function setDynamicFilter($iField, $fieldName) {
        $this->dynamicIField = $iField;
        $this->dynamicFieldName = $fieldName;
    }

    /**
     * Build dynamic filter sentence
     * 
     * @return void
     */
    public function buildDynamicFilter() {
        $this->filter .= " AND ".$this->dynamicIField->getColumnName()." ".ControllerFilter::$EQUALS." ";
        if(SQL_FORMAT[$this->dynamicIField->getFormat()]) {
            if($this->dynamicIField->getFormat() == 2)
                $this->filter .= " DATE('".$this->dynamicValue."')";
            else {
                $this->filter .= " '".$this->dynamicValue."' ";
            }
        } else {
            $this->filter .= $this->dynamicValue;
        }
    }

    /**
     * Add a primary key filter to OController
     * 
     * @param string $PKFieldName
     * 
     * @param string $value
     * 
     * @return void
     */
    public function addPKFilter($PKFieldName, $value) {
        $this->filter .= " AND ".$PKFieldName." = ".$value." ";
    }

    /**
     * Get where clausule
     * 
     * @return string
     */
    public function getFilter() {
        return $this->filter;
    }

    /**
     * Set dynamic value
     * 
     * @param string
     * 
     * @return void
     */
    public function setDynamicValue($value) {
        $this->dynamicValue = $value;
    }

    /**
     * Return dynamic field name to match
     * 
     * @return string
     */
    public function getDynamicFieldName() {
        return $this->dynamicFieldName;
    }
}
?>