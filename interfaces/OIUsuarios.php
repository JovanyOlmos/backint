<?php
namespace backint\interfaces;
require_once("./core/OInterface.php");
require_once("./definitions/SQLFormat.php");
use backint\core\OInterface;
class OIUsuarios extends OInterface {
    //Fields (Campos de interfaz)
    private $username;

    public function __construct(string $DBTableName, string $columnNameFromIdTable) {
        parent::__construct($DBTableName, $columnNameFromIdTable);
        $this->username = $this->addField("username", VARCHAR);
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername(IField $iField) {
        $this->username = $iField;
    }
}
?>