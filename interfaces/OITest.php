<?php
namespace backint\interfaces;
require_once("./core/OInterface.php");
require_once("./definitions/SQLFormat.php");
use backint\core\OInterface;

class OITest extends OInterface {
	//itk autocomplete start
	//itk autocomplete end
	public function __construct($tableName, $PKFieldName) {
		parent::__construct($tableName, $PKFieldName);
	}
}
?>