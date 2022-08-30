<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;

class MySQLOrderBuilder extends OrderBuilder {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Add a new field to sorting filter
     * 
     * @param Model 
     * 
     * @param ModelField field to include
     * 
     * @return void
     */
    public function addSort($model, $field) {
        $this->setSortingVar((strlen($this->getSortingVar()) > 0) ? "," : " ");
        $this->setSortingVar("`".$model->getModelName()."`.`".$field->getFieldName()."`");
    }

    /**
     * Get sorting
     * 
     * @return string MySQL order query
     */
    public function getSort() {
        return (strlen($this->getSortingVar()) > 0) ? " ORDER BY ".$this->getSortingVar()." ".$this->typeOrder : "";
    }
}
?>