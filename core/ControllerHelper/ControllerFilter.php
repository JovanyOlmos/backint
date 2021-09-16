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
}
?>