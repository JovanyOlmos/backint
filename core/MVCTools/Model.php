<?php
namespace backint\core;

use backint\core\db\types\iType;
use backint\core\ModelField;

class Model {
    
    /**
     * Model name
     * 
     * @var string
     */
    private $modelName;

    /**
     * Model Field as id
     * 
     * @var ModelField
     */
    private $idFieldModel = null;

    /**
     * Fields from the model. Array assoc with field name as key.
     * 
     * @var array
     */
    public $fields;

    /**
     * Constructor
     * 
     */
    public function __construct() {
        $this->fields = array();
        $this->modelName = "";
    }

    /**
     * Add a new field to a Model object
     * 
     * @param string
     * 
     * @param iType
     * 
     * @param mixed
     */
    public function addField($fieldName, $format, $mandatory = false, $default = null) {
        $modelField = new ModelField($fieldName, $format);
        if(!is_null($default))
        {
            $modelField->setDefault($default);
        }
        if($mandatory)
        {
            $modelField->setMandatory($mandatory);
        }
        $this->fields[$fieldName] = $modelField;
    }

    /**
     * Get the model name
     * 
     * @return string
     */
    public function getModelName() {
        return $this->modelName;
    }

    /**
     * Get id field
     * 
     * @return ModelField
     */
    public function getIdField() {
        return $this->idFieldModel;
    }

    /**
     * Set model's name
     * 
     * @param string
     */
    public function setModelName($modelName) {
        $this->modelName = $modelName;
    }

    /**
     * Set id field
     * 
     * @param ModelField
     */
    public function setIdField($idModelField) {
        $this->idFieldModel = $idModelField;
    }

    /**
     * Return the correct value or format when a field has a null value
     * 
     * @param ModelField
     * 
     * @return string
     */
    public static function nullPropagation($modelField) {
        if(isset($modelField) || $modelField->value == "" || $modelField == "null" || is_null($modelField->value))
        {
            if(is_null($modelField->getDefault()))
                return "null";
            return $modelField->getDefault();
        }
        return $modelField->value;
    }
}
?>