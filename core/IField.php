<?php
namespace backint\core;
require_once("./config/config.php");
class IField {
    private $sqlFormat; //DB Field Require Quotes to be saved
    private $columnName; //DB Field Name
    private $configTableName; //DB table's name where getting config
    public $fieldValue;

    public function __construct($DBColumnName, $sqlFormat){
        $this->configTableName = TABLE_CONFIG_PREFIX."_".$DBColumnName;
        $this->columnName = $DBColumnName;
        $this->sqlFormat = $sqlFormat;
    }

    public function getDBFormat() {
        return $this->sqlFormat;
    }

    public function getColumnName() {
        return $this->columnName;
    }

    public function getConfigurationTableName() {
        return $this->configTableName;
    }

    public function setDBFormat($type) {
        $this->sqlFormat = $type;
    }

    public function setColumnName($name) {
        $this->columnName = $name;
    }

    public function setConfigurationTableName($name) {
        $this->configTableName = $name;
    }
}
?>