<?php
namespace backint\interfaces;
require_once("./core/OInterface.php");
require_once("./definitions/SQLFormat.php");
use backint\core\OInterface;

class OITesting extends OInterface {
	//itk autocomplete start
	private $nombre, $activo, $fecha;
	//itk autocomplete end

	public function __construct($tableName, $PKFieldName) {
		parent::__construct($tableName, $PKFieldName);
		$this->nombre = $this->addField("nombre", VARCHAR);
		$this->activo = $this->addField("activo", BOOLEAN);
		$this->fecha = $this->addField("fecha", DATE);
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function setNombre($iField){
		$this->nombre = $iField;
	}

	public function getActivo(){
		return $this->activo;
	}

	public function setActivo($iField){
		$this->activo = $iField;
	}

	public function getFecha(){
		return $this->fecha;
	}

	public function setFecha($iField){
		$this->fecha = $iField;
	}

}
?>