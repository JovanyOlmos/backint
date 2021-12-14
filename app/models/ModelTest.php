<?php
namespace backint\models;

use backint\core\Model;
use SQL;

class ModelTest extends Model {
	//itk autocomplete start
	private $name, $default_date;
	//itk autocomplete end

	public function __construct() {
		parent::__construct();
		$this->setTableName("test");
		$this->setPKFieldName("id");
		$this->name = $this->addField("name", SQL::VARCHAR);
		$this->default_date = $this->addField("default_date", SQL::DATE, "NOW()");
	}

	public function getName(){
		return $this->name;
	}

	public function setName($field){
		$this->name = $field;
	}

	public function getDefault_date(){
		return $this->default_date;
	}

	public function setDefault_date($field){
		$this->default_date = $field;
	}

}
?>