<?php
namespace backint\core\db\builder;

class MySQLLimitBuilder extends LimitBuilder {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get limit's string
     * 
     * @return string
     */
    public function getLimit(): string {
        if(is_null($this->getRecords()) && is_null($this->getIndex()))
            return "";
        if(!is_null($this->getRecords()) && is_null($this->getIndex()))
            return " LIMIT ".$this->getRecords();
        return " LIMIT ".$this->getIndex().", ".$this->getRecords();
    }

}
?>