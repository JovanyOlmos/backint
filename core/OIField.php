<?php
namespace backint\core;
require_once("./config/config.php");
class OIField {
    //private ?string $inputType; //Field type
    private bool $sqlFormatRequireQuote; //DB Field Require Quotes to be saved
    private string $columnName; //DB Field Name
    private string $configTableName; //DB table's name where getting config
    private string $fieldValue;

    public function __construct(string $DBColumnName, bool $sqlFormatRequireQuote){
        $this->configTableName = TABLE_CONFIG_PREFIX."_".$DBColumnName;
        $this->columnName = $DBColumnName;
        $this->sqlFormatRequireQuote = $sqlFormatRequireQuote;
        //$this->inputType = $inputType;
    }

    /*public function getType() {
        return $this->inputType;
    }*/

    public function getDBFormat() {
        return $this->sqlFormatRequireQuote;
    }

    public function getColumnName() {
        return $this->columnName;
    }

    public function getConfigurationTableName() {
        return $this->configTableName;
    }

    public function getFieldValue() {
        return $this->fieldValue;
    }

    /*public function setType(string $type) {
        $this->inputType = $type;
    }*/

    public function setDBFormat(bool $type) {
        $this->sqlFormat = $type;
    }

    public function setColumnName(string $name) {
        $this->columnName = $name;
    }

    public function setConfigurationTableName(string $name) {
        $this->configTableName = $name;
    }

    public function setFieldValue(string $value) {
        $this->fieldValue = $value;
    }
}
?>