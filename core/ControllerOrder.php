<?php
namespace backint\core;
class ControllerOrder {
    private $iField;
    private $typeOrder;
    
    public function __construct($iField, $typeOrder) {
        $this->iField = $iField;
        $this->typeOrder = $typeOrder;
    }

    public function getFilter() {
        $orderBy = " ORDER BY ".$this->iField->getColumnName()." ".$this->typeOrder;
        return $orderBy;
    }
}
?>