<?php
namespace backint\core\QueryBuilder;

class WhereBuilder {

    /**
     * Static instance
     * 
     * @var WhereBuilder
     */
    private static $instance;

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
     * Dynamic Field
     * 
     * @var Field
     */
    private $dynamicField;

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
     * Init a where builder
     * 
     * @return WhereBuilder
     */
    public static function getInstance(): WhereBuilder {
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Add a filter to OController
     * 
     * @param Field $field
     * 
     * @param string $operator
     * 
     * @param string $value
     * 
     * @return void
     */
    public function addFilter($field, $operator, $value): void {
        $this->filter .= " AND ".$field->getColumnName()." ".$operator." ";
        if(SQL_FORMAT[$field->getFormat()]) {
            if($field->getFormat() == 2)
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
     * Set a future filter, wich one wait a dynamic value
     * 
     * @param Field $field
     * 
     * @param string $fieldName
     * 
     * @return void
     */
    public function setDynamicFilter($field, $fieldName): void {
        $this->dynamicField = $field;
        $this->dynamicFieldName = $fieldName;
    }

    /**
     * Build dynamic filter sentence
     * 
     * @return void
     */
    public function buildDynamicFilter(): void {
        $this->filter .= " AND ".$this->dynamicField->getColumnName()." ".WhereBuilder::$EQUALS." ";
        if(SQL_FORMAT[$this->dynamicField->getFormat()]) {
            if($this->dynamicField->getFormat() == 2)
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
    public function addPKFilter($PKFieldName, $value): void {
        $this->filter .= " AND ".$PKFieldName." = ".$value." ";
    }

    /**
     * Get where clausule
     * 
     * @return string
     */
    public function getFilter(): string {
        return $this->filter;
    }

    /**
     * Set dynamic value
     * 
     * @param string
     * 
     * @return void
     */
    public function setDynamicValue($value): void {
        $this->dynamicValue = $value;
    }

    /**
     * Return dynamic field name to match
     * 
     * @return string
     */
    public function getDynamicFieldName(): string {
        return $this->dynamicFieldName;
    }
}
?>