<?php
namespace backint\core\db\builder;

use Exception;

class MySQLDeleteBuilder extends DeleteBuilder {

    public function __construct() {
        parent::__construct();
        $this->setWhere(new MySQLWhereBuilder());
    }

    /**
     * Return query
     * 
     * @return string
     */
    public function getDelete(): string {
        if(is_null($this->getDeleteModel())) {
            throw new Exception("Model is not set", 1);
        }
        return "DELETE FROM `".$this->getDeleteModel()->getModelName()."` ".$this->where()->getFilter();
    }

}
?>