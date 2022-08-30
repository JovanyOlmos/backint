<?php
namespace backint\core\db\builder;

use Exception;

class MySQLUpdateBuilder extends UpdateBuilder {

    public function __construct() {
        parent::__construct();
        $this->setWhere(new MySQLWhereBuilder());
    }

    /**
     * Return query
     * 
     * @return string
     */
    public function getUpdate() {
        $query = "";
        if($this->getUpdateModel() == "") {
            throw new Exception("Model is not set", 1);
            die();
        }
        if(sizeof($this->getFields()) == 0) {
            throw new Exception("Fields are not set", 1);
            die();
        }
        $index = 0; 
        foreach ($this->getFields() as $fieldName => $fieldData) {
            if($index > 0) {
                $query .= ", ";
            }
            $query .= "`".$fieldName."` = ".($fieldData["format"]->hasQuotes() ?
                "'".$fieldData["value"]."'" : 
                $fieldData["value"]);
            $index++;
        }
        
        return "UPDATE `".$this->getUpdateModel()."` SET ".$query.$this->where()->getFilter().";";
    }
}
?>