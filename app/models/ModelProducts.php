<?php
namespace backint\app\models;

use backint\core\db\Defaults;
use backint\core\db\types\Date;
use backint\core\db\types\Decimal_10p2;
use backint\core\db\types\Decimal_10p4;
use backint\core\db\types\Double;
use backint\core\Model;
use backint\core\ModelField;
use backint\core\db\types\IntegerU;
use backint\core\db\types\LongString200;
use backint\core\db\types\String100;
use backint\core\db\types\String50;

class ModelProducts extends Model {

	public function __construct() {
		parent::__construct();
		$this->setModelName("products");
		$this->setIdField(new ModelField("id", IntegerU::instance()));
		$this->addField("price", Decimal_10p2::instance(), true, 1.00);
		$this->addField("name", String100::instance());
		$this->addField("description", LongString200::instance());
	}

	/**
	 * Get price model field
	 * 
	 * @return ModelField
	 */
	public function getPrice() {
		return $this->fields["price"];
	}

	/**
	 * Set price model field
	 * 
	 * @param ModelField
	 */
	public function setPrice($field) {
		$this->fields["price"] = $field;
	}

	/**
	 * Get name model field
	 * 
	 * @return ModelField
	 */
	public function getName() {
		return $this->fields["name"];
	}

	/**
	 * Set name model field
	 * 
	 * @param ModelField
	 */
	public function setName($field) {
		$this->fields["name"] = $field;
	}

	/**
	 * Get description model field
	 * 
	 * @return ModelField
	 */
	public function getDescription() {
		return $this->fields["description"];
	}

	/**
	 * Set description model field
	 * 
	 * @param ModelField
	 */
	public function setDescription($field) {
		$this->fields["description"] = $field;
	}
}
?>