<?php
namespace backint\core\db\builder;

use Exception;

class MySQLInsertBuilder extends InsertBuilder {

    /**
     * DB sentence fields
     * 
     * @var string
     */
    private $fieldsQuery;

    /**
     * DB sentence values
     * 
     * @var string
     */
    private $valuesQuery;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Return query
     * 
     * @return string
     */
    public function getInsert(): string {
        $this->valuesQuery = " VALUES (";
        if($this->getInsertModel() == "") {
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
                $this->fieldsQuery .= ", ";
                $this->valuesQuery .= ", ";
            }
            $this->fieldsQuery .= "`".$fieldName."`";
            $this->valuesQuery .= $fieldData["format"]->hasQuotes() && !$fieldData["not_include_quotes"] ? 
                "'".$fieldData["value"]."'" : 
                $fieldData["value"];
            $index++;
        }
        return "INSERT INTO ".$this->getInsertModel()." (".$this->fieldsQuery.")".$this->valuesQuery.");";
    }
}
?>