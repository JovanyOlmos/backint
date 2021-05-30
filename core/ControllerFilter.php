<?php
namespace backint\core;
require_once("./definitions/SQLFormat.php");
class ControllerFilter {
    private $filter = "";
    
    public function __construct() {
    }

    public function addFilter($iField, $operator, $value, $hasFilter) {
        if(!$hasFilter)
            $this->filter = $iField->getColumnName()." ".$operator." ";
        else
            $this->filter .= " AND ".$iField->getColumnName()." ".$operator." ";
        if(SQL_FORMAT[$iField->getDBFormat()]) {
            if($iField->getDBFormat() == 2)
                $this->filter .= " DATE(".$value.")";
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

    public function getFilter() {
        return $this->filter;
    }
}
?>