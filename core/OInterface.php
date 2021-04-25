<?php
namespace backint\core;
require_once("./core/IField.php");
require_once("./config/config.php");
use backint\core\IField;
class OInterface {
    private $DBTableName;
    private $columnNameFromIdTable;
    private $configurationTable;
    private $idObject = 0;
    public $objectFields;

    public function __construct($DBTableName, $columnNameFromIdTable) {
        $this->objectFields = array();
        $this->DBTableName = $DBTableName;
        $this->columnNameFromIdTable = $columnNameFromIdTable;
        $this->configurationTable = TABLE_CONFIG_PREFIX."_".$DBTableName;
    }

    public function addField($DBColumnName, $sqlFormat) {
        $iField = new IField($DBColumnName, $sqlFormat);
        $this->objectFields[$DBColumnName] = $iField;
        return $iField;
    }

    public function getDBTableName() {
        return $this->DBTableName;
    }

    public function getColumnNameFromIdTable() {
        return $this->columnNameFromIdTable;
    }

    public function getConfigurationTable() {
        return $this->configurationTable;
    }

    public function getIdObject() {
        return $this->idObject;
    }

    public function setIdObject($id) {
        $this->idObject = $id;
    }
}
?>