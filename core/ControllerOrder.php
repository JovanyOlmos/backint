<?php
namespace backint\core;
class ControllerOrder {
    private IField $iField;
    private string $typeOrder;
    
    public function __construct(IField $iField, string $typeOrder) {
        $this->iField = $iField;
        $this->typeOrder = $typeOrder;
    }

    public function getFilter() {
        $orderBy = " ORDER BY ".$this->iField->getColumnName()." ".$this->typeOrder;
        return $orderBy;
    }
}
?>