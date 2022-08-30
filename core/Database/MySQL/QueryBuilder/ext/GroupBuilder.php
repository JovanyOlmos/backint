<?php
namespace backint\core\db\builder;

class MySQLGroupBuilder extends GroupBuilder {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get group by sentence
     * 
     * @return string
     */
    public function getGroupBy(): string {
        if(is_null($this->getModel()) || is_null($this->getField()))
            return "";
        return " GROUP BY `".$this->getModel()->getModelName()."`.`".$this->getField()->getFieldName()."`";
    }

}
?>