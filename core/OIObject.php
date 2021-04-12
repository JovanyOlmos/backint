<?php
namespace backint\core;
require_once("./core/OIField.php");
require_once("./config/config.php");
use backint\core\OIField;
class OIObject {
    private ?string $DBTableName;
    private ?string $columnNameFromIdTable;
    private ?string $configurationTable;
    private ?int $idObject;
    private ?array $objectFields;

    public function __construct(string $DBTableName, string $columnNameFromIdTable) {
        $this->objectFields = array();
        $this->DBTableName = $DBTableName;
        $this->columnNameFromIdTable = $columnNameFromIdTable;
        $this->configurationTable = TABLE_CONFIG_PREFIX."_".$DBTableName;
    }

    public function addField(string $DBColumnName, bool $sqlFormatRequireQuote) {
        $oiField = new OIField($DBColumnName, $sqlFormatRequireQuote);
        $this->objectFields[$DBColumnName] = $oiField;
        return $oiField;
    }

    public function getDBTableName() {
        return $this->DBTableName;
    }

    public function getColumnNameFromIdTable() {
        return $this->columnNameFromIdTable;
    }

    public function getFields() {
        return $this->objectFields;
    }

    public function getConfigurationTable() {
        return $this->configurationTable;
    }

    public function getIdObject() {
        return $this->idObject;
    }

    public function setIdObject(int $id) {
        $this->idObject = $id;
    }
}
?>