<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;

abstract class WhereBuilder {

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
        $this->filter = "";
    }

    /**
     * Add a filter to Model Controller
     * 
     * @param Model $model
     * 
     * @param ModelField $field
     * 
     * @param string $operator
     * 
     * @param mixed $value
     * 
     * @return void
     */
    public abstract function addFilter($model, $field, $operator, $value);

    /**
     * Add a primary key filter to OController
     * 
     * @param Model
     * 
     * @param string $value
     * 
     * @return void
     */
    public abstract function addPKFilter($model, $value);

    /**
     * Get where clausule
     * 
     * @return string
     */
    public function getFilter() {
        return $this->filter;
    }

    /**
     * Set where clausule
     * 
     * @param string
     * 
     * @return void
     */
    public function concatFilter($filter) {
        $this->filter .= $filter;
    }
}
?>