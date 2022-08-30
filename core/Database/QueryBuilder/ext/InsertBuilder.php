<?php
namespace backint\core\db\builder;

use backint\core\db\Defaults;
use backint\core\Model;
use backint\core\ModelField;
use Error;

abstract class InsertBuilder {

    /**
     * Array with all fields to insert
     * 
     * @var array
     */
    private $fields;

    /**
     * Insert into model
     * 
     * @var string
     */
    private $into;

    /**
     * Constructor
     */
    public function __construct() {
        $this->into = "";
        $this->fields =  array();
    }

    /**
     * Add a model field into the insert list
     * 
     * @param ModelField
     * 
     * @return void
     */
    public function addField($modelField) {
        $value = $modelField->value;
        if($modelField->value == null || !isset($modelField->value) || $modelField->value == "") {
            if($modelField->getMandatory()) {
                if(is_null($modelField->getDefault()) || $modelField->getDefault() == "")
                    throw new Error("Field ".$modelField->getFieldName()." cannot be empty");
            }
            if(!is_null($modelField->getDefault()) && $modelField->getDefault() != "") {
                if(is_object($modelField->getDefault()) && get_class($modelField->getDefault()) == Defaults::class)
                    $value = $modelField->getDefault()->value;
                else
                    $value = $modelField->getDefault();
            }
        }

        $this->fields[$modelField->getFieldName()] = array(
            "value" => $value, 
            "format" => $modelField->getFormat(),
            "not_include_quotes" => (is_object($modelField->getDefault()) 
                && get_class($modelField->getDefault()) == Defaults::class 
                && !$modelField->getDefault()->useQuotes) ? true : false
        );
    }

    /**
     * Get insert list
     * 
     * @return mixed
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Set main model to insert
     * 
     * @param Model
     * 
     * @return void
     */
    public function setInsertModel($model) {
        $this->into = $model->getModelName();
    }

    /**
     * Get main model to insert
     * 
     * @return string
     */
    public function getInsertModel() {
        return $this->into;
    }

    /**
     * Return query
     * 
     * @return string
     */
    public abstract function getInsert(): string;
}
?>