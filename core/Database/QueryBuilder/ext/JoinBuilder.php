<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;

abstract class JoinBuilder {

    /**
     * Data source joins
     * 
     * @var string
     */
    private $unions;

    /**
     * Constructor
     */
    public function __construct() {
        $this->unions = "";
    }

    /**
     * Add a new data source and establish its relationship in the query
     * 
     * @param Model $firstModel
     * 
     * @param Model $secondModel
     * 
     * @param ModelField $firstFieldMatch
     * 
     * @param ModelField $secondFieldMatch
     * 
     * @param string $unionType
     * 
     * @return void
     */
    public abstract function addJoin($firstModel, $secondModel, $firstFieldMatch, $secondFieldMatch, $unionType);

    /**
     * Get joins
     * 
     * @return string
     */
    public function getJoin() {
        return $this->unions;
    }

    /**
     * Set joins
     * 
     * @param string
     * 
     * @return void
     */
    public function setJoin($unions) {
        $this->unions .= $unions;
    }
    
}
?>