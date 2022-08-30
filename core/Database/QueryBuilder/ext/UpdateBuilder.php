<?php
namespace backint\core\db\builder;

use backint\core\Model;
use backint\core\ModelField;
use Error;

abstract class UpdateBuilder {

    private $where;

    /**
     * Array with all fields to update
     * 
     * @var array
     */
    private $fields;

    /**
     * Update model
     * 
     * @var string
     */
    private $model;

    /**
     * Construct
     */
    public function __construct() {
        $this->fields = array();
    }

    /**
     * Add a model field into the update list
     * 
     * @param ModelField
     * 
     * @return void
     */
    public function addField($modelField) {
        $value = $modelField->value;
        if($modelField->value == null || !isset($modelField->value) || $modelField->value == "") {
            if($modelField->getMandatory()) {
                throw new Error("Field ".$modelField->getFieldName()." cannot be empty");
            }
        }

        $this->fields[$modelField->getFieldName()] = array(
            "value" => $value, 
            "format" => $modelField->getFormat()
        );
    }

    /**
     * Return fields
     * 
     * @return mixed
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Set main model to update
     * 
     * @param Model
     * 
     * @return void
     */
    public function setUpdateModel($model) {
        $this->model = $model->getModelName();
    }

    /**
     * Get main model to update
     * 
     * @return string
     */
    public function getUpdateModel() {
        return $this->model;
    }

    /**
     * Return query
     * 
     * @return string
     */
    public abstract function getUpdate();

    /**
     * Get where builder
     * 
     * @return WhereBuilder
     */
    public function where() {
        return $this->where;
    }

    /**
     * Set where builder
     * 
     * @param WhereBuilder
     * 
     * @return void
     */
    public function setWhere($where) {
        $this->where = $where;
    }
}
?>