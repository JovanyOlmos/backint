<?php
namespace backint\core;
require_once("./config/config.php");
class IField {
    private int $sqlFormat; //DB Field Require Quotes to be saved
    private string $columnName; //DB Field Name
    private string $configTableName; //DB table's name where getting config
    public string $fieldValue;

    public function __construct(string $DBColumnName, int $sqlFormat){
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

    public function setDBFormat(int $type) {
        $this->sqlFormat = $type;
    }

    public function setColumnName(string $name) {
        $this->columnName = $name;
    }

    public function setConfigurationTableName(string $name) {
        $this->configTableName = $name;
    }
}
?>