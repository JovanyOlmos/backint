<?php
namespace backint\interfaces;
require_once("./core/OInterface.php");
require_once("./definitions/SQLFormat.php");
use backint\core\OInterface;

class OITest extends OInterface {
	//itk autocomplete start
	private $nombre;
	//itk autocomplete end

	public function __construct($tableName, $PKFieldName) {
		parent::__construct($tableName, $PKFieldName);
		$this->nombre = $this->addField("nombre", VARCHAR);
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function setNombre($iField){
		$this->nombre = $iField;
	}

}
?>